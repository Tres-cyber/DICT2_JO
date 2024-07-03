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

/* base.twig */
class __TwigTemplate_926f7e6002705c8b33bbc0cfa28b7fa3 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'head' => [$this, 'block_head'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        yield "<!doctype html>
<html lang=\"en\">
  <head>
    <meta charset=\"UTF-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <link rel=\"icon\" href=\"favicon.ico\">
    ";
        // line 8
        yield from $this->unwrap()->yieldBlock('head', $context, $blocks);
        // line 10
        yield "    ";
        yield Dict\Jo\ViteUtil::vite("main.ts");
        yield "
  </head>

  <html>
    <body>
      <noscript>
        <strong>
          We're sorry but DICT JO doesn't work properly without JavaScript enabled. Please enable it to continue.
        </strong>
      </noscript>
      <div id=\"app\">
        ";
        // line 21
        yield from $this->unwrap()->yieldBlock('body', $context, $blocks);
        // line 23
        yield "      </div>
    </body>
  </html>
</html>
";
        return; yield '';
    }

    // line 8
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "    ";
        return; yield '';
    }

    // line 21
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "base.twig";
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
        return array (  85 => 21,  77 => 8,  68 => 23,  66 => 21,  51 => 10,  49 => 8,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "base.twig", "/var/www/html/app/views/base.twig");
    }
}
