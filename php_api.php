<?php
    // PHP API v1.1
    class Fetch_api
    {
        private $api_url;
        private $data;
        private $type;
        
        function __construct($api_url, $type = null)
        {
            $this->api_url = $api_url;
            $this->data = $this->fetchData($this->api_url);
            $this->type = $type;
        }
        
        private function fetchData($api_url)
        {
            $response = @file_get_contents($api_url);
            if ($response === false) {
                return null;
            }
            $data = json_decode($response, true);
            if ($data === null) {
                return null;
            }
            return $data;
        }
        
        public function getData()
        {
            return $this->data;
        }
        
        private function simpler($attachs) {
            $i = 0;
            $stringAttachs = "";
            foreach ($attachs as $key => $value) {
                if ($i == 0) {
                    $mark = "?";
                } else {
                    $mark = "&";
                }
                $stringAttachs .= $mark.$key."=".$value;
                ++$i;
            }
            return $this->fetchData($this->api_url.$stringAttachs);
        }
        
        public function attach($attachs, $method = 'GET', $headers = [])
        {
            if ($this->type == "simpler") {
                return $this->simpler($attachs);
            }
            $options = [
                'http' => [
                    'method' => $method,
                    'header' => implode("\r\n", $headers),
                ],
            ];
            
            if ($method === 'GET') {
                $attachString = $this->coreAttach($attachs);
                $url = $this->api_url . $attachString;
            } else {
                $options['http']['content'] = http_build_query($attachs);
                $url = $this->api_url;
            }
            
            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return null;
            }
            
            $data = json_decode($response, true);
            if ($data === null) {
                return null;
            }
            
            return $data;
        }
        
        private function coreAttach($attachs)
        {
            return '?' . http_build_query($attachs);
        }
    }
    