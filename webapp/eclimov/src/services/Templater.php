<?php

namespace Service;

use \Core\Service;

class Templater extends Service implements TemplaterService
{
    public function process_template(string $template_name, array $variables = null): string
    {
        $template_content = file_get_contents(TEMPLATES_PATH.'/'.$template_name.'.html');
        if ($variables) {
            foreach ($variables as $variable_name => $variable_value) {
                $template_content = str_replace('{{'.$variable_name.'}}', $variable_value, $template_content);
            }
        }

        return preg_replace('/\{\{[\w]{1,}\}\}/', '', $template_content); //Clean all variables placeholders left
    }
}