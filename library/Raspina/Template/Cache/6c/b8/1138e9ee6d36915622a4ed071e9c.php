<?php

/* index.php */
class __TwigTemplate_6cb81138e9ee6d36915622a4ed071e9c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo ": index page : (";
        echo twig_escape_filter($this->env, (isset($context["name1"]) ? $context["name1"] : null), "html", null, true);
        echo ") (";
        echo twig_escape_filter($this->env, (isset($context["name2"]) ? $context["name2"] : null), "html", null, true);
        echo ")

<form action=\"\" method=\"post\">

name: <input type=\"text\" name=\"data[name]\" /><br />

family: <input type=\"text\" name=\"data[family]\" /><br />

tel: <input type=\"text\" name=\"data[tel]\" /><br />
<input type=\"submit\" name=\"sub\" value=\"sumit\" />
</form>

";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["valid"]) ? $context["valid"] : null), "html", null, true);
    }

    public function getTemplateName()
    {
        return "index.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 13,  17 => 1,);
    }
}
