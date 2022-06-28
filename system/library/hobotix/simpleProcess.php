<?php

namespace hobotix;

class simpleProcess
{
   private $pid      = null;
   private $file     = null;
   private $route    = null;
   private $params   = null;
   private $config   = null;

   public function __construct($params = []){
      if (!is_dir(DIR_PIDS)){
         mkdir(DIR_PIDS, 0755, true);
      }

      if (is_cli() && $params){
         $this->pid           = getmypid();
         $this->args          = $params['args'];
         $this->route         = $params['route'];
         $this->config        = $params['config'];
         $this->file          = DIR_PIDS . md5(json_encode($params)) . '.pid';

         //Проверка, работает ли процесс сейчас
         if ($process = $this->getProcess($params)){
            if (!empty($process['running'])){
               echoLine('[CLI] Процесс уже запущен, стоп');
               die();
            }
         }

         echoLine('[CLI] Процесс начат, pid: ' . $this->pid);
         echoLine('[CLI] Процесс начат, file: ' . $this->file);

      }
   }

   private function write($data){
      file_put_contents($this->file, json_encode($data));
   }
   
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

   public function dropProcess(){
      unlink($this->file);
   }


   public function getProcess($params = []){
      $file = DIR_PIDS . md5(json_encode($params)) . '.pid';
      
      $result = [];
      if (file_exists($file)){
         $result = json_decode(file_get_contents($file), true);         

         $result['success']         = true;
          $result['never']          = false;
         $result['finished']        = ($result['status'] == 'finished');
         $result['running']         = posix_getpgid($result['pid']);
         $result['failed']          = ($result['status'] == 'started' && !$result['running']);


      } else {
         $result['success'] = false;
         $result['never'] = true;
      }

      return $result;
   }

    public function getProcesses(){
         $files = glob(DIR_PIDS . '*.pid');

         foreach($files as $file){

         }

    }


















}