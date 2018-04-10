<?php

namespace Service;

use \Core\Service;

class Templater extends Service implements TemplaterService
{
    private $template_content;

    public function process_template(string $template_name, array $variables = array()): string
    {
        $this->template_content = file_get_contents(TEMPLATES_PATH.'/'.$template_name.'.html');

        $this->template_content = $this->process_variables($this->template_content, $variables);
        $this->template_content = $this->process_conditions($this->template_content, $variables);
        $this->template_content = $this->process_loops($this->template_content, $variables);

        $this->template_content = $this->clean_placeholders($this->template_content);

        return $this->template_content;
    }

    public function process_variables($content, array $variables){
        $result = $content;
        foreach ($variables as $variable_name => $variable_value) {
            if(!\is_array($variable_value)){
                $result = str_replace('{{'.$variable_name.'}}', $variable_value, $result);
            }
        }
        return $result;
    }

    public function process_conditions($content, array $variables){
        $result = $content;
        foreach ($variables as $variable_name => $variable_value) {
            //print_r($variable_value);
            if(!\is_array($variable_value) && $variable_value && false !== strpos($result, '{% if '.$variable_name.' %}')){
                $result = preg_replace(
                    '/(\{% if '.$variable_name.' %\})([\s\S]*)(\{% endif %\})/',
                    '$2', //Replace with the 2nd group
                    $result
                );
            }
        }
        return $result;
    }

    public function process_loops($content, array $variables){
        $result = $content;
        foreach ($variables as $variable_name => $variable_value) {
            if(\is_array($variable_value) && \count($variable_value)){
                preg_match_all(
                    '/\{% foreach '.$variable_name.' as ([\w]*) %\}([\s\S]*)\{% endforeach %\}/',
                    $result,
                    $matches);
                if(\count($matches[1]) && \count($matches[2])){
                    $iteration_alias = trim($matches[1][0]);
                    $iteration_template_part = $matches[2][0];
                    $iteration_template_part = str_replace($iteration_alias.'.', '', $iteration_template_part);

                    $iteration_template_part_processed = '';
                    foreach ($variable_value as $loop_vars) {
                        $iteration_template_part_processed .= $this->process_variables($iteration_template_part, $loop_vars);
                    }
                    $result = preg_replace(
                        '/\{% foreach '.$variable_name.' as [\w]* %\}[\s\S]*\{% endforeach %\}/',
                        $iteration_template_part_processed,
                        $result
                    );
                }
            }
        }
        return $result;
    }

    public function clean_placeholders($content){
        $result = $content;

        $p_cond_handlers = '/\{% if [\w]* %\}[\s\S]*\{% endif %\}/';
        $p_loop_handlers = '/\{% foreach [\w]* as [\w]* %\}[\s\S]*\{% endforeach %\}/';
        $p_var_handlers = '/\{\{[\w]*\}\}/';

        $result =
            preg_replace(
                $p_cond_handlers,
                '',
                preg_replace(
                    $p_loop_handlers,
                    '',
                    preg_replace(
                        $p_var_handlers,
                        '',
                        $result
                    )
                )
            ); //Clean all variables placeholders left
        return $result;
    }
}