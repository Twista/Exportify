<?php

namespace Exportify;

/**
 * Exportify class
 * provides export (mainly XML)
 * @author Michal Haták <me@twista.cz>
 */
class Exportify {

    /** @var string */
    private $template;

    /** @var string */
    private $header;

    /** @var array[] */
    private $variables = array();

    public function __construct($template = NULL) {
	if (!empty($template))
	    $this->setTemplate($template);
    }

    /**
     * 
     * @param string $file
     * @return \Exportify\Exportify
     */
    public function setTemplate($file) {
	$this->template = $file;
	return $this;
    }

    /**
     * gets Variable
     * @param string $key
     * @return mixed
     */
    public function &__get($key) {
	if (array_key_exists($key, $this->variables)) {
	    return $this->variables[$key];
	}
    }

    /**
     * sets variable
     * @param string $key
     * @param mixed $val
     */
    public function __set($key, $val) {
	$this->variables[$key] = (is_array($val)) ? json_decode(json_encode($val), FALSE) : $val;
    }

    /**
     * set header 
     * @param string $header
     * @return \Exportify\Exportify
     */
    public function setHeader($header) {
	$this->header = $header;
	return $this;
    }

    /**
     * parse template file
     * @return string
     * @throws \Exception
     */
    public function parse() {
	if (is_file($this->template)) {
	    $keys = array(
		'{if %%}' => '<?php if (\1): ?>',
		'{elseif %%}' => '<?php ; elseif (\1): ?>',
		'{for %%}' => '<?php for (\1): ?>',
		'{foreach %%}' => '<?php foreach (\1): ?>',
		'{/if}' => '<?php endif; ?>',
		'{/foreach}' => '<?php endforeach; ?>',
		'{$%%}' => '<?php echo $\1; ?>',
		'<?xml' => '<?php print "<?xml" ?>', // necessary for xml e.g.: 
	    );

	    foreach ($keys as $key => $val) {
		$patterns[] = '#' . str_replace('%%', '(.+)', preg_quote($key, '#')) . '#U';
		$replace[] = $val;
	    }

	    /* replace our pseudo language in template with php code */
	    return preg_replace($patterns, $replace, file_get_contents($this->template));
	} else {
	    throw new \Exception("Missing template file '$this->template'.");
	}
    }

    /**
     * render template
     * @return string
     */
    public function render() {
	// print header
	if (!empty($this->header))
	    header($this->header);

	// parse template file
	$code = $this->parse();

	// extract variables
	if (!empty($this->variables))
	    extract($this->variables);

	return eval('?>' . $code);
    }

}