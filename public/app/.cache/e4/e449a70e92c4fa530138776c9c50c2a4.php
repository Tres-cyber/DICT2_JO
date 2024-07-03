<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* signin.twig */
class __TwigTemplate_39d02dbee304dbcadfb03409e2b690a7 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'head' => [$this, 'block_head'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base.twig", "signin.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "<title>DICT JO - Sign IN</title>
<link rel=\"stylesheet\" href=\"./styles/style.css\">
";
        return; yield '';
    }

    // line 6
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "<div class=\"login-container\">
  <div class=\"login-logo-wrapper\">
    <img src=\"assets/logo.png\" alt=\"\" class=\"login-logo\">
  </div>
  <div class=\"login-wrapper-2\">
    <img src=\"assets/logo.png\" alt=\"\" class=\"login-logo-two\">
    <div class=\"login-box\">
      <h1 class=\"login-title\">login</h1>
      <form action=\"\">
        <label class=\"input-title\" for=\"\">Email</label>
        <input class=\"input-wrapper\" type=\"text\">
        <label class=\"input-title\" for=\"\">Password</label>
        <input class=\"input-wrapper\" type=\"text\">
        <input class=\"input-submit self-center\" type=\"submit\" value=\"Submit\">
      </form>
    </div>
  </div>
</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "signin.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  58 => 6,  48 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "signin.twig", "/var/www/html/app/views/signin.twig");
    }
}
