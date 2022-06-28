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
            $data = json_decode(file_get_contents($file), true);

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

   }

    public function getProcesses(){
         $files = glob(DIR_PIDS . '*.pid');

         foreach($files as $file){
            
         }

    }


















}