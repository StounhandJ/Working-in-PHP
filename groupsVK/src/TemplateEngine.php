<?php


namespace App;


use Smarty;

class TemplateEngine
{
    /**
     * @param string $name
     * @param array $data
     * @return false|string
     * @throws \SmartyException
     */
    static function respond(string $name, array $data)
    {
        $smarty = new Smarty;
        $smarty->setTemplateDir(__DIR__ . "/../Template");
        $smarty->setCompileDir(__DIR__ . "/../Template/compile");

        foreach ($data as $key => $value) {
            $smarty->assign($key, $value);
        }

        return $smarty->fetch($name . '.tpl');
    }
}