<?php
namespace Rpmeir\TSwiper;

use Adianti\Util\AdiantiTemplateHandler;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Template\THtmlRenderer;
use ApplicationTranslator;

/**
 * TSwiperItem Widget
 *
 * @version    v1.0.0
 * @package    tswiper
 * @author     Rodrigo Pires Meira
 */
class TSwiperItem extends TElement
{

    private $templatePath;
    private $template;
    private $object;

    /**
     * Class Constructor
     * @param object $object Object with template attributes
     * @param string $template HTML string template content
     * @param string $path HTML string file path
     */
    public function __construct($object, $template, $path = '')
    {
        parent::__construct('div');
        $this->{'class'} = 'swiper-slide';
        $this->setObject($object);
        $this->setTemplate($template);
        if(!empty($path))
        {
            $this->setTemplatePath($path);
        }
    }
    
    /**
     * Set swiper item template for rendering
     * @param string $template  HTML tring template content
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
    /**
     * Set swiper item template for rendering
     * @param string $path Template path
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
    }
    
    /**
     * Set swiper object
     * @param object $object Intance object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }
    
    /**
     * Show the callendar and execute required scripts
     */
    public function show()
    {
        if (!empty($this->templatePath))
        {
            $html = new THtmlRenderer($this->templatePath);
            $html->enableSection('main');
            $html->enableTranslation();
            $html = AdiantiTemplateHandler::replace($html->getContents(), $this->object);
            
            return $html;
        }

        if (!empty($this->template))
        {
            $item_content = new TElement('div');
            $item_template = ApplicationTranslator::translateTemplate($this->template);
            $item_template = AdiantiTemplateHandler::replace($item_template, $this->object);
            $item_content->add($item_template);
            parent::add($item_content);
        }
        
        parent::show();
    }
}
