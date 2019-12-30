<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_status extends Titan_Model {
	public function __construct() {
		parent::__construct();
    }

    private function convert($size) {
        $unit=array('B','KB','MB','GB','TB','PB');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    
    public function get_server_memory_usage() {
        $memory_usage = $this->convert(memory_get_usage(true));
        return $memory_usage;
    }

    public function get_server_cpu_usage() {
        if(strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0){
            exec('wmic cpu get LoadPercentage', $p);
            return $p[1]."%";
        } else {
            # top -l 2 -n 0 -F | egrep -o ' \d*\.\d+% idle' | tail -1 | awk -F% -v prefix="$prefix" '{ printf "%s%.1f%%\n", prefix, 100 - $1 }'
            $cpu_usage = shell_exec("ps aux | awk {'sum+=$3;print sum'} | tail -n 1");
            return $cpu_usage."%";
        }
    }

    public function get_server_load_avg() {
        $serverload = array();

        // DIRECTORY_SEPARATOR checks if running windows
        if(DIRECTORY_SEPARATOR != '\\') {
            if(function_exists("sys_getloadavg")) {
                // sys_getloadavg() will return an array with [0] being load within the last minute.
                $serverload = sys_getloadavg();
                $serverload[0] = round($serverload[0], 4);
            } else if(@file_exists("/proc/loadavg") && $load = @file_get_contents("/proc/loadavg")) {
                $serverload = explode(" ", $load);
                $serverload[0] = round($serverload[0], 4);
            }
            if(!is_numeric($serverload[0])) {
                if(@ini_get('safe_mode') == 'On') {
                    return "Unknown";
                }
    
                // Suhosin likes to throw a warning if exec is disabled then die - weird
                if($func_blacklist = @ini_get('suhosin.executor.func.blacklist')) {
                    if(strpos(",".$func_blacklist.",", 'exec') !== false) {
                        return "Unknown";
                    }
                }
                // PHP disabled functions?
                if($func_blacklist = @ini_get('disable_functions')) {
                    if(strpos(",".$func_blacklist.",", 'exec') !== false) {
                        return "Unknown";
                    }
                }
    
                $load = @exec("uptime");
                $load = explode("load average: ", $load);
                $serverload = explode(",", $load[1]);
                if(!is_array($serverload)) {
                    return "Unknown";
                }
            }
        } else {
            $wmi = new COM("Winmgmts://");
            $server = $wmi->execquery("SELECT LoadPercentage FROM Win32_Processor");  
            $cpu_num = 0;
            $load_total = 0;
            foreach($server as $cpu)
            {
                $cpu_num++;
                $load_total += $cpu->loadpercentage;
            }

            $load = round($load_total/$cpu_num);
            return $load;
        }
    
        $returnload = trim($serverload[0]);
        return $returnload;
    }

    // check responsetime for a webbserver
    public function get_server_response_time($domain){
        $starttime = microtime(true);
        // supress error messages with @
        $file      = @fsockopen($domain, 80, $errno, $errstr, 10);
        $stoptime  = microtime(true);
        $status    = 0;

        if (!$file){
            $status = -1;  // Site is down
        }
        else{
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        return $status."ms";
    }
}