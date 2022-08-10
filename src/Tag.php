<?php
declare(strict_types = 1);
namespace Proner\PhpPimaco;

use Proner\PhpPimaco\Tags\Barcode;
use Proner\PhpPimaco\Tags\Img;
use Proner\PhpPimaco\Tags\P;
//use Proner\PhpPimaco\Tags\QrCode;

class Tag
{
    private $content;

    private $width;
    private $height;
    private $border;
    private $size;
    private $padding;
    private $marginLeft;
    private $template;

    /**
     * Tag constructor.
     * @param string|null $content
     */
    public function __construct(array $tagConfig = null)
    {
        $this->template = $tagConfig;
        $this->tags = new \ArrayObject();

    }

    /**
     * @param string $template
     * @param string $path
     */
    public function loadConfig(array $template)
    {
        $this->width = $template['tag']['width'];
        $this->height = $template['tag']['height'];
        $this->marginLeft =$template['tag']['margin-left'];

        if (empty($this->border)) {
            $this->border = $template['tag']['border'];
        }

        if (empty($this->padding)) {
            $this->padding = 0;
        }

        if (isset($template['tag']['ln'])) {
            $this->ln = $template['tag']['ln'];
        }

        if (isset($template['tag']['align'])) {
            $this->align = $template['tag']['align'];
        }
    }

    /**
     * @param $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param float $padding
     * @return $this
     */
    public function setPadding(float $padding)
    {
        $this->padding = $padding;
        return $this;
    }

    /**
     * @param float $border
     * @return $this
     */
    public function setBorder(float$border)
    {
        $this->border = $border;
        return $this;
    }

    /**
     * @param P $p
     * @return P
     */
    public function addP(P $p)
    {
        $this->tags->append($p);
        return $p;
    }

    /**
     * @param string $content
     * @return P
     */
    public function p(string $content)
    {
        $p = new P($content);
        $this->tags->append($p);
        return $p;
    }

    /**
     * @param Barcode $barcode
     * @return Barcode
     */
    public function addBarcode(Barcode $barcode)
    {
        $this->tags->append($barcode);
        return $barcode;
    }

    /**
     * @param string $content
     * @param null $typeCode
     * @return Barcode
     */
    public function barcode(string $content, $typeCode = null)
    {
        $barcode = new Barcode($content, $typeCode);
        $this->tags->append($barcode);
        return $barcode;
    }

    // /**
    //  * @param string $content
    //  * @param string|null $label
    //  * @param string|null $fontSize
    //  * @return QrCode
    //  */
    // public function qrcode(string $content, string $label = null, float $fontSize = 5)
    // {
    //     $qrcode = new QrCode($content);

    //     if ($label) {
    //         $qrcode->setLabel($label);
    //     }

    //     if ($fontSize) {
    //         $qrcode->setLabelFontSize($fontSize);
    //     }

    //     $this->tags->append($qrcode);
    //     return $qrcode;
    // }

    /**
     * @param $content
     * @return Img
     */
    public function img($content)
    {
        $img = new Img($content);
        $this->tags->append($img);
        return $img;
    }

    /**
     * @return array
     */
    private function getTags()
    {
        return $this->tags->getArrayCopy();
    }

    /**
     * @param null $side
     * @param bool $margin
     * @return string
     */
    public function render($side = null, $margin = false)
    {
        $this->content = "";

        if (!empty($side)) {
            $style[] = "float: {$side}";
        }
        if ($margin) {
            $style[] = "margin-left: {$this->marginLeft}mm";
        }
        if (!empty($this->width)) {
            $style[] = "width: {$this->width}mm";
        }
        if (!empty($this->height)) {
            $style[] = "height: {$this->height}mm";
        }
        if (!empty($this->border)) {
            $style[] = "border: {$this->border}mm solid black";
        }
        if (!empty($this->size)) {
            $style[] = "font-size: {$this->size}mm";
        }
        if (!empty($this->align)) {
            $style[] = "text-align: {$this->align}";
        }
        $tags = $this->getTags();
        foreach ($tags as $tag) {
            $this->content .=  $tag->render();
        }
        
        if (!empty($style)) {
            $this->content = "<div style='".implode(";", $style).";'><div style='padding: {$this->padding}mm;'>{$this->content}</div></div>";
        } else {
            $this->content = "<div><div style='padding: {$this->padding}mm;'>{$this->content}</div></div>";
        }
     //   dd($this->content);
        return $this->content;
    }
}
