<?php

namespace Service;

interface TemplaterService {
    public function process_template(string $template_name, array $variables = array());
}