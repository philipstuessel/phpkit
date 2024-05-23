<?php
// v3.0
$configFilePath = __DIR__ . "/pyconfig.json";

function loadConfig($configFilePath)
{
    if (!file_exists($configFilePath)) {
        return false;
    }
    $configContent = file_get_contents($configFilePath);
    return json_decode($configContent, true);
}

function runPython($pythonScriptPath)
{
    global $configFilePath;
    $config = loadConfig($configFilePath);
    if (!$config) {
        return "Error loading configuration file.";
    }
    $pythonPath = $config['pythonPath'];
    $pythonVersion = $config['pythonVersion'];

    $output = array();
    $pythonScriptPath = script($pythonScriptPath);
    exec("$pythonPath$pythonVersion $pythonScriptPath 2>&1", $output, $returnCode);
    if ($returnCode === 0) {
        return implode("\n", $output);
    } else {
        return "Error PythonAPI executing Python script. Return code: $returnCode\n" . implode("\n", $output);
    }
}


function script($script)
{
    $ending = ".py";
    if (substr($script, -strlen($ending)) === $ending) {
        return $script;
    } else {
        return $script . ".py";
    }
}

class Python
{
    private $data;
    function __construct($data = null)
    {
        $this->data = $data;
    }

    public function getAlong(...$value)
    {
        global $configFilePath;
        $config = loadConfig($configFilePath);
        if (!$config) {
            return "Error loading configuration file.";
        }
        $pythonPath = $config['pythonPath'];
        $pythonVersion = $config['pythonVersion'];

        $pythonScriptPath = script($this->data);
        $command = "$pythonPath$pythonVersion $pythonScriptPath " . implode(' ', $value);
        $output = shell_exec("$command 2>&1");

        if ($output !== null) {
            return trim($output);
        } else {
            return "Error executing Python script.";
        }
    }

    function v()
    {
        global $configFilePath;
        $config = loadConfig($configFilePath);
        if (!$config) {
            echo "Error loading configuration file.";
            return;
        }
        $pythonPath = $config['pythonPath'];
        $pythonVersion = $config['pythonVersion'];
        echo shell_exec("$pythonPath$pythonVersion --version 2>&1");
    }
}
