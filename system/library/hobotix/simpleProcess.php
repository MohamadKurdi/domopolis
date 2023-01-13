<?php

namespace hobotix;

class simpleProcess
{  

   /*
      mode can be ['route'] or ['md5']], 'route' makes pid files from route name, md5 - from all params
   */
   private $mode              = 'route'; 
   private $pid               = null;
   private $file              = null;   
   private $route             = null;
   private $params            = null;
   private $config            = null;
   private $cronConfig        = null;
   private $prefix            = null;


   /*
      Constructor
   */
   public function __construct($params = [], $configFilesPrefix = null){
      if (!is_dir(DIR_PIDS)){
         mkdir(DIR_PIDS, 0775, true);
      }

      $this->cronConfig = loadJSONConfig('cron');

      if (is_cli() && $params){
         $this->pid           = getmypid();         
         $this->prefix        = $configFilesPrefix;
         $this->args          = $params['args'];
         $this->route         = $params['route'];
         $this->config        = $params['config'];
         $this->params        = $params;
         $this->file          = $this->pidname();    
         
         if ($process = $this->getProcess($params)){
            if (!empty($process['running'])){
               echoLine('[simpleProcess] Process already running, stop');
               foreach ($process as $key => $info){
                  if (is_array($info)){
                      echoLine('[simpleProcess] Process ' . $key . ' = ' . implode(',', $info));
                  } else {
                     echoLine('[simpleProcess] Process ' . $key . ' = ' . $info);
                  }
               }
               die();
            }
         }

         if ($this->prefix){
              echoLine('[simpleProcess] Config file is using prefixes ' . $this->prefix);
         }

         echoLine('[simpleProcess] Process started with pid: ' . $this->pid);
         echoLine('[simpleProcess] Process started with pidfile: ' . $this->file);
      }
   }

   /*
      Creates pid file name, using mode or just route
   */
   private function pidname($params = []){
      if (!$params){
         $params = $this->params;
      }


      if ($this->mode == 'md5'){
         return DIR_PIDS . md5(json_encode($params)) . '.pid';
      } else {
         return DIR_PIDS . str_replace('/', '.', $params['route']) . '.pid';
      }
   }

   /*
      This function starts writes information to pid file
   */
   private function write($data){
      file_put_contents($this->file, json_encode($data));
   }
   
   /*
      This function starts process and writes information to pid file
   */
   public function startProcess(){
      if ($this->pid && $this->file){
         $data = [
            'pid'       => $this->pid,
            'route'     => $this->route,
            'args'      => $this->args,
            'config'    => $this->config,
            'start'     => date('Y-m-d H:i:s'),
            'status'    => 'started'
         ];

         $this->write($data);
      }
   }   

   /*
      This function stops process successfully or not
   */
   public function stopProcess(){
      if ($this->pid && $this->file){

         if (file_exists($this->file)){
            $data = json_decode(file_get_contents($this->file), true);

            $data['stop']     = date('Y-m-d H:i:s');
            $data['status']   = 'finished';
         } else {
            $data = [
               'pid'    => $this->pid,
               'route'  => $this->route,
               'args'   => $this->args,
               'config' => $this->config,
               'start'  => date('Y-m-d H:i:s'),
               'stop'   => date('Y-m-d H:i:s'),
               'status' => 'finished without start'
            ];
         }

         $this->write($data);
      }
   }

   /*
      This function drops current process pid file
   */
   public function dropProcess(){
      unlink($this->file);
   }

   /*
      This function drops any process pid file
   */
   public function dropPidFile($file){
      unlink(DIR_PIDS . $file);
   }

   /*
      This function checks if one of the processes is running now
   */
   public function isRunning($route, $config = false, $args = []){

      if ($this->getRouteConfig($route)){

         if (!$config){
            $config = $this->getRouteConfig($route)['config'];
         }         

         if ($this->prefix && strpos($config, '.' . $this->prefix . '.') === false){
            $config = str_replace('config.', 'config.' . $this->prefix . '.', $config);          
         }         

         if ($process = $this->getProcess(['route' => $route, 'config' => $config, 'args' => $args])){
            if (!empty($process['running'])){
               echoLine('[simpleProcess] Process ' . $route . ' is running now');
               return true;
            } 
         }

      }

      echoLine('[simpleProcess] Process ' . $route . ' is not running now');
      return false;
   }

    /*
      This function gets one process information from pid file
   */
   public function getProcess($params = [], $file = false){

      if (!$file){
         $file = $this->pidname($params);
      }
      
      $result = [];
      if (file_exists($file)){
         $result = json_decode(file_get_contents($file), true);         

         $result['success']         = true;
         $result['never']           = false;
         $result['file']            = basename($file);
         $result['main']            = (!empty($this->cronConfig[$result['route']]))?$this->cronConfig[$result['route']]['main']:false;
         $result['group']           = (!empty($this->cronConfig[$result['route']]))?$this->cronConfig[$result['route']]['group']:'';
         $result['name']            = (!empty($this->cronConfig[$result['route']]))?$this->cronConfig[$result['route']]['name']:$result['route'];
         $result['finished']        = ($result['status'] == 'finished');
         $result['running']         = posix_getpgid($result['pid']);
         $result['failed']          = ($result['status'] == 'started' && !$result['running']);
         $result['stop']            = (!empty($result['stop'])?$result['stop']:false);


      } else {
         $result['success']   = false;
         $result['file']      = $file;
         $result['never']     = true;
      }

      return $result;
   }


   /*
      This function returns a list of all processes
   */
   public function getProcesses(){
      $files = glob(DIR_PIDS . '*.pid');

      $results = [];
      foreach($files as $file){
         $results[] = $this->getProcess([], $file);
      }

      return $results;
   }

   /*
      This function returns all route cron configs
   */
   public function getCronConfig(){
      return $this->cronConfig;
   }

   /*
      This function returns single route config
   */
   public function getRouteConfig($route){

      if (!empty($this->cronConfig[$route])){
         return [
            'config' => $this->cronConfig[$route]['config'],
            'args'   => $this->cronConfig[$route]['args']
         ];
      }


      return false;
   }
}