<?php
# v1.2
class Json {
    private $data;
    private $file;
    function __construct($file)
    {
        $this->file = $file;
        $this->data = json_decode(file_get_contents($file), true);
    }
    
    public function get($item) {
        if (isset($this->data[$item])) {
            if ($this->data[$item] === null) {
                return ["error", "null"];
            } else {
                return $this->data[$item];
            }
        } else {
            return ["error", "undefined_key"];
        }
    }

    public function getall() {
        return $this->data;
    }
    
    public function set($key, $value) {
        $this->data[$key] = $value;
    }
    
    public function remove($key) {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }
    
    public function save($data) {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    public function exists($key) {
        return isset($this->data[$key]);
    }
    
    public function count() {
        return count($this->data);
    }
    
    public function clear() {
        $this->data = [];
    }
}

class Open {
    private $file;
    private $type;

    function __construct($file, $type = null) {
        $this->file = $file;
        $this->type = $type;
    }
    
    function read() {
        return file_get_contents($this->file, true);
    }
    
    function write($content) {
        $this->create();
        $w_file = fopen($this->file, "w");
        
        if ($w_file === false) {
            echo "Error opening file.";
            return false;
        }
        if (fwrite($w_file, $content) === false) {
            echo "Error writing to file.";
            fclose($w_file);
            return false;
        }
        fclose($w_file);
        return true;
    }
    
    function json($value = null) {
        if ($this->type == "r") {
            return (new Json($this->file))->getall();
        } elseif ($this->type == "w") {
            $this->create();
            return (new Json($this->file))->save($value);
        }
    }
    function create() {
        $folder = dirname($this->file);
        if (!is_dir($folder)) {
            if (!mkdir($folder, 0777, true)) {
                echo "Error creating folder.";
                return false;
            }
        }
    }
    
}
    
function printt($value) {
    if (is_array($value)) {
        print_r($value);
    } else {
        echo $value;
    }
}
    
function p($value) {
    printt($value);
}

function reloadjs() {
    printt("<script>window.location.reload();</script>");
}
    
function js($java_script) {
    printt("<script>{$java_script}</script>");
}