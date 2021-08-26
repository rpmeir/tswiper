<?php
namespace Rpmeir\TSwiper;

use Adianti\Widget\Base\TElement;
use Adianti\Widget\Base\TScript;
use Adianti\Widget\Base\TStyle;

/**
 * TSwiper Widget
 *
 * @version    v1.0.0
 * @package    tswiper
 * @author     Rodrigo Pires Meira
 */
class TSwiper extends TElement
{

    private $wrapper;
    private $templatePath;
    private $itemTemplate;
    private $pagination; // position bug on progressbar type, should stay on top of wrapper
    private $arrows; // minimal position bug on prev button
    private $scrollbar;
    private $breakpoints;
    private $options;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct('div');
        $this->{'class'} = 'tswiper';
        $this->{'id'} = 'tswiper_' . mt_rand(1000000000, 1999999999);
        $this->items = [];
        $this->pagination = FALSE;
        $this->arrows = FALSE;
        $this->scrollbar = FALSE;
        $this->breakpoints = [];
        $this->options = [];

        $this->wrapper = new TElement('div');
        $this->wrapper->{'class'} = 'swiper-wrapper';
        
        parent::add($this->wrapper);
    }
    
    /**
     * Set extra tswiper options (ex: effect, grabCursor, direction, spaceBetween...)
     * @param string $option Name of available option in Swiper
     * @param mixed $value Value to set for option
     */
    private function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }

    /**
     * Get extra tswiper options (ex: effect, grabCursor, direction, spaceBetween...)
     * @return string $options Json encode with current options 
     */
    public function getOptions()
    {
        $options = json_encode($this->options);
        return $options;
    }
    
    /**
     * Add item
     * @param object $object Item data object
     * @param string $template String with html template
     * @param string $path String with path of html file template
     * @return object Instance of SwiperItem
     */
    public function addItem($object, $template = '', $path = '')
    {
        $html_emplate = !empty($template) ? $template : $this->itemTemplate;
        $file_path = !empty($path) ? $path : $this->templatePath;
        $swiperItem = new TSwiperItem($object, $html_emplate, $file_path);
        $this->wrapper->add($swiperItem);

        return $swiperItem;
    }
    
    /**
     * Set swiper item template for rendering
     * @param string $template   String with html template content
     */
    public function setItemTemplate($template)
    {
        $this->itemTemplate = $template;
    }
    
    /**
     * Set item min height
     * @param int $height min height
     */
    public function setItemHeight($height)
    {
        $this->itemHeight = $height;
    }
    
    /**
     * Set swiper item template for rendering
     * @param string $path   HTML template file path
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;
    }
    
    /**
     * Add breakpoint
     * @param int $pixeslWidth Width of view to apply this breakpoint
     * @param int $slidesPerView Number of slides per view
     * @param int $spaceBetween Pixels width in space between slides 
     */
    public function addBreakPoint($pixelsWidth, $slidesPerView, $spaceBetween)
    {
        $this->breakpoints[$pixelsWidth] = [
            'slidesPerView' => $slidesPerView, 
            'spaceBetween' => $spaceBetween
        ];
    }
    
    /**
     * Set direction slides
     * @param string $direction Direction [ horizontal | vertical ]
     */
    public function setDirection($direction)
    {
        $allowed = ['horizontal', 'vertical'];
        if(in_array($direction, $allowed))
        {
            $this->setOption('direction', $direction);
        }
    }

    /**
     * Set effect transition
     * @param string $effect Effect type on transition ['slide', 'fade', 'cube', 'coverflow', 'flip']
     */
    public function setEffect($effect)
    {
        $allowed = ['slide', 'fade', 'cube', 'coverflow', 'flip'];
        if(in_array($effect, $allowed))
        {
            $this->setOption('effect', $effect);
        }
    }

    /**
     * Set free mode option
     * @param boolean $disableMomentum Disable keep moving
     */
    public function enableFreeMode($disableMomentum = false)
    {
        $this->setOption('freeMode', true);
        if($disableMomentum)
        {
            $this->setOption('freeModeMomentum', false);
        }
    }
    
    /**
     * Set space between slides
     * @param int $space Pixels between slides
     */
    public function setSpaceBetween($space = 0)
    {
        if(is_numeric($space) && $space >= 0)
        {
            $this->setOption('spaceBetween', $space);
        }
    }
    
    /**
     * Set slides per view
     * @param int $number Number of slides per view, or 'auto'
     * @param boolean $grouped Aply same number per group
     */
    public function setSlidesPerView($number, $grouped = false)
    {
        if(is_numeric($number))
        {
            $this->setOption('slidesPerView', $number);
            if(is_bool($grouped) && $grouped)
            {
                $this->setOption('slidesPerGroup', $number);
            }
        }
    }
    
    /**
     * Set speed duration
     * @param int $miliseconds Duration of transition between slides
     */
    public function setSpeed($miliseconds)
    {
        if(is_numeric($miliseconds))
        {
            $this->setOption('speed', $miliseconds);
        }
    }
    
    /**
     * Set slides per column // with bug
     * @param int $number Number of slides per column
     */
    public function setSlidesPerColumn($number)
    {
        if(is_numeric($number))
        {
            $this->setOption('slidesPerColumn', $number);
        }
    }

    /**
     * Set Centered slides
     * @param boolean $bounds Boolean with the bounds option
     */
    public function centerSlides($bounds = false)
    {
        $this->setOption('centeredSlides', true);
        if(is_bool($bounds))
        {
            $this->setOption('centeredSlidesBounds', $bounds);
        }
    }
    
    /**
     * Enable arrows
     */
    public function enableArrows()
    {
        $this->arrows = TRUE;
        $this->setOption('navigation', [
            'nextEl' => '#swiper-button-next_' . mt_rand(1000000000, 1999999999), 
            'prevEl' => '#swiper-button-prev_' . mt_rand(1000000000, 1999999999)
        ]);
    }
    
    /**
     * Enable scrollbar
     * @param boolean $hide Hide scroll bar automatically
     */
    public function enableScrollbar($hide = true)
    {
        $this->scrollbar = TRUE;
        $this->setOption('scrollbar', [ 
            'el' => '#swiper-scrollbar_' . mt_rand(1000000000, 1999999999), 
            'hide' => $hide 
        ]);
    }
    
    /**
     * Enable pagination
     * @param string $type Pagination type ['bullets', 'fraction', 'progressbar', 'custom']
     * @param boolean $clickable Enable click event on bullet
     * @param boolean $dynamicBullets Enable dinamic bullets
     */
    public function enablePagination($type = 'bullets', $clickable = false, $dynamicBullets = false)
    {
        $this->pagination = TRUE;

        $opt = [];
        $allowedTypes = ['bullets', 'fraction', 'progressbar', 'custom'];

        $opt['el'] = '#swiper-pagination_' . mt_rand(1000000000, 1999999999);

        $opt['type'] = in_array($type, $allowedTypes) ? $type : null;
        $opt['clickable'] = $clickable;
        $opt['dynamicBullets'] = $dynamicBullets;

        $this->setOption('pagination', $opt);
    }
    
    /**
     * Show the callendar and execute required scripts
     */
    public function show()
    {

        $options = $this->options;

        if($this->pagination)
        {
            $page = new TElement('div');
            $page->{'class'} = 'swiper-pagination';
            $page->{'id'} = substr($options['pagination']['el'], 1);
            parent::add($page);
        }

        if($this->arrows)
        {
            $prev = new TElement('div');
            $prev->{'class'} = 'swiper-button-prev';
            $prev->{'id'} = substr($options['navigation']['prevEl'], 1);
            parent::add($prev);
            $next = new TElement('div');
            $next->{'class'} = 'swiper-button-next';
            $next->{'id'} = substr($options['navigation']['nextEl'], 1);
            parent::add($next);
        }

        if($this->scrollbar)
        {
            $scrl = new TElement('div');
            $scrl->{'class'} = 'swiper-scrollbar';
            $scrl->{'id'} = substr($options['scrollbar']['el'], 1);
            $scrl->{'style'} = 'margin-top: 8px;';
            parent::add($scrl);
        }

        // create options
        if($this->breakpoints)
        {
            $this->options['breakpoints'] = $this->breakpoints;
        }

        $options = json_encode($this->options);

        TStyle::importFromFile('vendor/rpmeir/tswiper/src/lib/css/swiper-bundle.min.css');
        TScript::importFromFile('vendor/rpmeir/tswiper/src/lib/js/swiper-bundle.min.js');

        TStyle::importFromFile('vendor/rpmeir/tswiper/src/lib/css/tswiper.css');

        // Sets 500 ms timeout to load dependencies 
        TScript::create("$(function(){var swiper = new Swiper('#$this->id', $options);});", true, 500);

        parent::show();
    }
}
