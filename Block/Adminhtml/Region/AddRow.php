<?php

namespace Leeto\RegionManager\Block\Adminhtml\Region;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Phrase;

class AddRow extends Container
{
    /**
     * Initialize Imagegallery Images Edit Block.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_objectId = 'row_id';
        $this->_blockGroup = 'Leeto_RegionManager';
        $this->_controller = 'adminhtml_region';
        if ($this->_isAllowedAction('Leeto_RegionManager::add_row')) {
            $this->buttonList->update('save', 'label', __('Save'));
        } else {
            $this->buttonList->remove('save');
        }
        $this->buttonList->remove('reset');
    }

    /**
     * Retrieve text for header element depending on loaded image.
     *
     * @return Phrase
     */
    public function getHeaderText()
    {
        return __('Add Row Data');
    }

    /**
     * Check permission for passed action.
     *
     * @param string $resourceId
     *
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get form action URL.
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }

        return $this->getUrl('*/*/save');
    }
}
