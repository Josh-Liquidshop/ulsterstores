<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Pgrid
 */
class Amasty_Pgrid_Block_Adminhtml_Catalog_Product_Grid_Renderer_Thumb extends Amasty_Pgrid_Block_Adminhtml_Catalog_Product_Grid_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $html = '';
        if (!$row->getEntityId()) {
            return '';
        }
        try {
            $size = Mage::helper('ampgrid')->getGridThumbSize();

            if ('carousel' == Mage::getStoreConfig('ampgrid/attr/image_type')) {
                $product = Mage::getModel('catalog/product')->load($row->getEntityId());

                $productImages = $this->getMediaGalleryImages($product);

                $images = array();
                foreach ($productImages as $image) {
                    if ($image->getUrl()) {
                        $resizedImageUrl = $this->helper('catalog/image')->init($product, 'thumbnail', $image->getFile())->resize($size);

                        $images[] = '
                        <a href="' . $image->getUrl() . '" rel="lightbox[zoom' . $row->getEntityId() . ']">
                            <img src="' . $resizedImageUrl . '" width="' . $size . '" height="' . $size . '" />
                        </a>';
                    }
                }

                $visibleSlided = 1;
                if (count($images) > $visibleSlided) {
                    $html = $this->getCarouselHtml($images, $row->getEntityId(), $visibleSlided);
                } else {
                    $html = '
                    <div class="am_middle">
                        <div class="am_inner" align="center">
                            ' . implode('', $images) . '
                        </div>
                    </div>
                ';
                }
            } else {
                $imageAttr = 'thumbnail';
                if (Mage::getStoreConfig('ampgrid/attr/image_type')) {
                    $imageAttr = Mage::getStoreConfig('ampgrid/attr/image_type');
                }
                if (!$row->getData($imageAttr)) {
                    $product = Mage::getModel('catalog/product')->load($row->getEntityId());
                    if ($product->getData($imageAttr)) {
                        $row->setData($imageAttr, $product->getData($imageAttr));
                    }
                }

                $url = Mage::helper('catalog/image')->init($row, $imageAttr)->resize($size)->__toString();
                $zoomUrl = '';
                if (Mage::getStoreConfig('ampgrid/attr/zoom')) {
                    $zoomUrl = Mage::helper('catalog/image')->init($row, $imageAttr)->__toString();
                }
                if ($url) {
                    if ($zoomUrl) {
                        $html .= '<a href="' . $zoomUrl . '" rel="lightbox[zoom' . $row->getEntityId() . ']">';
                    }
                    $html .= '<img src="' . $url . '" alt="" width="' . $size . '" height="' . $size . '" />';
                    $html .= '</a>';
                }
            }
        } catch (Exception $e) {
            /* no file uploaded */
        }

        return $html;
    }

    /**
     * Carousel html getter
     *
     * @param array $images
     * @param string $carouselId
     * @param int $visibleSlided
     * @return string
     */
    protected function getCarouselHtml($images, $entityId, $visibleSlided = 1)
    {
        $carouselId = 'carousel_' . $entityId;
        $widthImg = 75;
        $paddingImg = 2;
        $widthWithPadding = $widthImg + $paddingImg;
        $scrollerWidth = 40;

        $carouselWidth = (($widthWithPadding) * $visibleSlided + $scrollerWidth);

        return '
            <div class="carousel" id="' . $carouselId . '" style="width: ' . $carouselWidth . 'px;">
                    <a href="javascript:" class="carousel-control prev" rel="prev">&lt;</a>
                    <a href="javascript:" class="carousel-control next"  rel="next">&gt;</a>
                <div class="am_middle" style="width: ' . $visibleSlided * ($widthWithPadding) . 'px;">
                    <div class="am_inner" style="width: ' . (count($images) * ($widthWithPadding)) . 'px;">
                        ' . implode('', $images) . '
                    </div>
                </div>
            </div>
                <script>
                    var carouselElement = $(\'' . $carouselId . '\');
                    new Carousel(
                        carouselElement.down(\'.am_middle\'), 
                        carouselElement.down(\'.am_inner\').select(\'img\'), 
                        carouselElement.select(\'a\'), {
                                duration: 0.7,
                                visibleSlides: ' . $visibleSlided . '
                    });
                </script>
        ';
    }

    protected function getMediaGalleryImages($product)
    {
        if(!$product->hasData('media_gallery_images') && is_array($product->getMediaGallery('images'))) {
            $images = new Varien_Data_Collection();
            foreach ($product->getMediaGallery('images') as $image) {
                if (!Mage::getStoreConfig('ampgrid/attr/display_disabled_images')
                    && $image['disabled']) {
                    continue;
                }
                $image['url'] = $product->getMediaConfig()->getMediaUrl($image['file']);
                $image['id'] = isset($image['value_id']) ? $image['value_id'] : null;
                $image['path'] = $product->getMediaConfig()->getMediaPath($image['file']);
                $images->addItem(new Varien_Object($image));
            }
            $product->setData('media_gallery_images', $images);
        }

        return $product->getData('media_gallery_images');
    }
}
