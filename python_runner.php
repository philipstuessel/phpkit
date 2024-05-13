<?php
    // v2.0
    function runPython($data) {
        $pythonScriptPath = $data;
        $output = array();
        exec("python3 $pythonScriptPath 2>&1", $output, $returnCode);
        if ($returnCode === 0) {
            return implode("\n", $output);
        } else {
            return "Error PythonAPI executing Python script. Return code: $returnCode\n" . implode("\n", $output);
        }
    }

    class Python {
        private $data;
        function __construct($data = null)
        {
            $this->data = $data;
        }

        public function getAlong(...$value) {
            $pythonScriptPath = $this->data;
            $command = "python3 $pythonScriptPath " . implode(' ', $value);
            $output = shell_exec("$command 2>&1");
            
            if ($output !== null) {
                return trim($output);
            } else {
                return "Error executing Python script.";
            }
        }

        function v() {
            echo shell_exec("python3 --version 2>&1");
        }
}