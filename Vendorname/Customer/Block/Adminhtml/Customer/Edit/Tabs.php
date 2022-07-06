<?php
namespace Vendorname\Customer\Block\Adminhtml\Customer\Edit;
 
use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
/**
* Customer information - Vendorname CUstomer Input Tab form block
*/
class Tabs extends Generic implements TabInterface
{
    /**
    * @var \Magento\Store\Model\System\Store
    */
   protected $_systemStore;
   /**
    * Core registry
    *
    * @var \Magento\Framework\Registry
    */
   protected $_coreRegistry;
 
   /**
    * @param \Magento\Backend\Block\Template\Context $context
    * @param \Magento\Framework\Registry $registry
    * @param array $data
    */
   public function __construct(
       \Magento\Backend\Block\Template\Context $context,
       \Magento\Framework\Registry $registry,
       \Magento\Framework\Data\FormFactory $formFactory,
       \Magento\Store\Model\System\Store $systemStore,
       \Magento\Directory\Model\Config\Source\Country $countryFactory,
       \Magento\Customer\Api\CustomerRepositoryInterface $customerRepo,
       \Vendorname\Customer\Model\Source\InterestedAreaOptions $interestedAreaOptions,
       array $data = []
   ) {
       $this->_coreRegistry = $registry;
       $this->_systemStore = $systemStore;
       $this->_countryFactory = $countryFactory;
       $this->_customerRepo = $customerRepo;
       $this->_interestedAreaOptions = $interestedAreaOptions;
       parent::__construct($context, $registry, $formFactory, $data);
   }
 
   /**
    * @return string|null
    */
   public function getCustomerId()
   {
       return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
   }
 
   /**
    * @return \Magento\Framework\Phrase
    */
   public function getTabLabel()
   {
       return __('Custom Input Tab');
   }
 
   /**
    * @return \Magento\Framework\Phrase
    */
   public function getTabTitle()
   {
       return __('Custom Input Tab');
   }
 
   /**
    * @return bool
    */
   public function canShowTab()
   {
       if ($this->getCustomerId()) {
           return true;
       }
       return false;
   }
 
   /**
    * @return bool
    */
   public function isHidden()
   {
      if ($this->getCustomerId()) {
           return false;
       }
       return true;
   }
 
   /**
    * Tab class getter
    *
    * @return string
    */
   public function getTabClass()
   {
       return '';
   }
 
   /**
    * Return URL link to Tab content
    *
    * @return string
    */
   public function getTabUrl()
   {
       return '';
   }
 
   /**
    * Tab should be loaded trough Ajax call
    *
    * @return bool
    */
   public function isAjaxLoaded()
   {
       return false;
   }

   public function initForm()
   {
       if (!$this->canShowTab()) {
           return $this;
       }
       /**@var \Magento\Framework\Data\Form $form */
       $form = $this->_formFactory->create();
       $form->setHtmlIdPrefix('myform_');
 
       $customerId = $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
       $customer = $this->_customerRepo->getById($customerId);
 
       if(null !== $customer->getCustomAttribute('company_name')) {
           $companyName = $customer->getCustomAttribute('company_name')->getValue();
       } else {
           $companyName = '';
       }
       $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Vendorname Customer Input')]);
       $fieldset->addField(
           'company_name',
           'text',
           [
               'name' => 'customer[company_name]',
               'data-form-part' => $this->getData('target_form'),
               'label' => __('Company / Institiute Name'),
               'title' => __('Company / Institiute Name'),
               'value' => $companyName,
           ]
       );
 
       if(null !== $customer->getCustomAttribute('designation')) {
           $designation = $customer->getCustomAttribute('designation')->getValue();
       } else {
           $designation = '';
       }
       $fieldset->addField(
           'designation',
           'text',
           [
               'name' => 'customer[designation]',
               'data-form-part' => $this->getData('target_form'),
               'label' => __('Designation'),
               'title' => __('Designation'),
               'value' => $designation,
           ]
       );
 
       if(null !== $customer->getCustomAttribute('gst_number')) {
           $gstNumber = $customer->getCustomAttribute('gst_number')->getValue();
       } else {
           $gstNumber = '';
       }
       $fieldset->addField(
           'gst_number',
           'text',
           [
               'name' => 'customer[gst_number]',
               'data-form-part' => $this->getData('target_form'),
               'label' => __('GST/VAT No'),
               'title' => __('GST/VAT No'),
               'value' => $gstNumber,
               'maxlength' => 15,
           ]
       )->setAfterElementHtml("
            <script type=\"text/javascript\">
                require([
                    'jquery',
                    'mage/template',
                    'jquery/ui',
                    'mage/translate'
                ],
                function($, mageTemplate) {     
                    $('#myform_gst_number').on('change', function(event){
                        var gstno = $('#myform_gst_number').val();
                        var reggst = /^([0-9]){2}([A-Z a-z]){5}([0-9]){4}([A-Z a-z]){1}([0-9]){1}([A-Z a-z]){1}([A-Z a-z 0-9]){1}?$/;
                        if(!reggst.test(gstno) && gstno!=''){ 
                            $('.lblerror_gst').remove();
                            $('<span style=\'color:#e22626;border: 1px solid #e22626;background: #fff8d6;padding: 6px 10px 10px;color: #555;font-size: 12px;margin: 2px 0 0;\' class=\'lblerror_gst\'>GST No. is not valid It should be in this \"11AAAAA1111Z1A1\" format</span>').insertAfter('#myform_gst_number');
                            $('#myform_gst_number').css('border', '1px solid #e22626');
                            $('#myform_gst_number').focus();
                            $('#save').prop('disabled', true);
                            return false;
                        } else {
                            $('#myform_gst_number').css('border', '1px solid #adadad');
                            $('#save').prop('disabled', false);
                            $('.lblerror_gst').hide();
                        }
                    });
                    $('#myform_gst_number').on('keypress', function(e){
                        var code = ('charCode' in e) ? e.charCode : e.keyCode;
                        if (
                            !(code > 47 && code < 58) && // numeric (0-9)
                            !(code > 64 && code < 91) && // upper alpha (A-Z)
                            !(code > 96 && code < 123)) { // lower alpha (a-z)
                            e.preventDefault();
                        }
                    })
                });
            </script>"
        ); 
 
       if(null !== $customer->getCustomAttribute('contact_number')) {
           $contactNumber = $customer->getCustomAttribute('contact_number')->getValue();
       } else {
           $contactNumber = '';
       }   
       $fieldset->addField(
           'contact_number',
           'text',
           [
               'name' => 'customer[contact_number]',
               'data-form-part' => $this->getData('target_form'),
               'label' => __('Contact Number'),
               'title' => __('Contact Number'),
               'value' => $contactNumber,
               'type' => 'number',
               'minlenght' => 7,
               'maxlength' => 15,
           ]
       )->setAfterElementHtml("
            <script type=\"text/javascript\">
                require([
                    'jquery',
                    'mage/template',
                    'jquery/ui',
                    'mage/translate'
                ],
                function($, mageTemplate) {     
                    $('#myform_contact_number').on('keypress', function(evt){
                        evt = (evt) ? evt : window.event;
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                            return false;
                        }
                        return true;
                    });
                });
            </script>"
        ); 
 
       if(null !== $customer->getCustomAttribute('countryid')) {
           $countryId = $customer->getCustomAttribute('countryid')->getValue();
       } else {
           $countryId = '';
       }  
       $optionsc = $this->_countryFactory->toOptionArray();
       $country = $fieldset->addField(
           'countryid',
           'select',
           [
               'name' => 'customer[countryid]',
               'data-form-part' => $this->getData('target_form'),
               'label' => __('Country'),
               'title' => __('Country'),
               'required'=> false,
               'values' => $optionsc,
           ]
       );
 
       if(null !== $customer->getCustomAttribute('stateid')) {
           $stateId = $customer->getCustomAttribute('stateid')->getValue();
       } else {
           $stateId = '';
       }
       $fieldset->addField(
           'stateid',
           'select',
           [
               'name' => 'customer[stateid]',
               'data-form-part' => $this->getData('target_form'),
               'label' => __('State/Province'),
               'title' => __('State/Province*'),
               'required'=> false,
               'values' =>  ['--Please Select State/Province--'],
           ]
       );


       if(null !== $customer->getCustomAttribute('custom_state')) {
            $customState = $customer->getCustomAttribute('custom_state')->getValue();
        } else {
            $customState = '';
        }
        $fieldset->addField(
            'custom_state',
            'text',
            [
                'name' => 'customer[custom_state]',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('State/Province'),
                'title' => __('State/Province*'),
                'required'=> false,
                'value' =>  $customState,
            ]
        )->setAfterElementHtml("
            <script type=\"text/javascript\">
                require([
                    'jquery',
                    'mage/template',
                    'jquery/ui',
                    'mage/translate'
                ],
                function($, mageTemplate) {     
                    $('#myform_custom_state').on('keypress', function(e){
                        var code = ('charCode' in e) ? e.charCode : e.keyCode;
                        if (
                            !(code == 13) && // enter
                            !(code == 32) && // space
                            !(code > 64 && code < 91) && // upper alpha (A-Z)
                            !(code > 96 && code < 123)) { // lower alpha (a-z)
                            e.preventDefault();
                        }
                    });
                });
            </script>"
        ); 

        if(null !== $customer->getCustomAttribute('is_custom_state')) {
            $isCustomState = $customer->getCustomAttribute('is_custom_state')->getValue();
        } else {
            $isCustomState = '0';
        }
        $fieldset->addField(
            'is_custom_state',
            'text',
            [
                'name' => 'customer[is_custom_state]',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('is_custom_state'),
                'title' => __('is_custom_state'),
                'required'=> false,
                'value' =>  $isCustomState,
            ]
        );
 
       if(null !== $customer->getCustomAttribute('interested_area')) {
           $interestedArea = $customer->getCustomAttribute('interested_area')->getValue();
       } else {
           $interestedArea = '0';
       }
       $interestedAreaOptions = $this->_interestedAreaOptions->getAllOptions();
       $fieldset->addField(
           'interested_area',
           'multiselect',
           [
               'name' => 'customer[interested_area]',
               'label' => __('Interested Area (Select atleast 1 item)'),
               'title' => __('Interested Area (Select atleast 1 item)'),
               'class' => 'main_acount',
               'data-form-part' => $this->getData('target_form'),
               'values' => $interestedAreaOptions,
           ]
       );

       if(null !== $customer->getCustomAttribute('sap_customer_code')) {
            $sap_customer_code = $customer->getCustomAttribute('sap_customer_code')->getValue();
        } else {
            $sap_customer_code = '';
        }
        $fieldset->addField(
            'sap_customer_code',
            'text',
            [
                'name' => 'customer[sap_customer_code]',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('SAP Customer Code'),
                'title' => __('SAP Customer Code'),
                'value' => $sap_customer_code,
            ]
        );
 
       if(null !== $customer->getCustomAttribute('is_agreed')) {
           $isAgreed = $customer->getCustomAttribute('is_agreed')->getValue();
       } else {
           $isAgreed = '0';
       }
       $fieldset->addField(
           'is_agreed',
           'checkbox',
           [
               'name' => 'customer[is_agreed]',
               'label' => __('I agree to the Terms & Conditions'),
               'title' => __('I agree to the Terms & Conditions'),
               'data-form-part' => $this->getData('target_form'),
               'value' => $isAgreed,
               'onchange' => 'this.value = this.checked;'
           ]
       );
       /*
       * Add Ajax to the Country select box html output
       */
       $country->setAfterElementHtml("  
           <script type=\"text/javascript\">
                   require([
                   'jquery',
                   'mage/template',
                   'jquery/ui',
                   'mage/translate'
               ],
               function($, mageTemplate) {
                   if($isAgreed == 1) {
                       $('#myform_is_agreed').prop('checked', 'true');
                   }
                  
                   $('#myform_countryid').val('$countryId');
                   $('.field-countryid').prop('class', 'admin__field field field-countryid  with-addon required _required');
                   $('#myform_countryid').prop('class', 'required-entry _required select admin__control-select');
                   
                   getState('onload');

                   $('.field-is_custom_state').hide();
                   if($isCustomState == '0') {
                        $('.field-custom_state').show();
                        $('.field-stateid').hide();
                   } else {
                        $('.field-custom_state').hide();
                        $('.field-stateid').show();
                   }
                
                   if( '$interestedArea' ) {
                    if('$interestedArea'.includes(',')) {
                        var array_data = '$interestedArea'.split(',');
                        for( var i=0; i < array_data.length; i++) {
                            $('#myform_interested_area').find('option[value='+ array_data[i] +']').prop('selected', 'selected');
                        }
                    } else {
                        $('#myform_interested_area').find('option[value=$interestedArea]').prop('selected', 'selected');
                    }
                   }

                  $('#myform_stateid').trigger('change');
                  $('#myform_countryid').on('change', function(event){
                      getState('change');
                  })
                  
                   function getState(result) {
                       var param = 'country='+$('#myform_countryid').val();
                       $.ajax({
                               url : window.location.protocol+'//'+window.location.host+'/customerregistration/index/registration/',
                               type: 'get',
                               data: param,
                               dataType: 'json',
                               showLoader:true,
                               success: function(data){
                                   $('#myform_stateid').empty();
                                   //data.value has the array of regions
                                   if(data.value.length == 0) {
                                        $('.field-stateid').hide();
                                        $('.field-custom_state').show().prop('class', 'admin__field field field-custom_state  with-addon required _required');
                                        $('#myform_custom_state').val('').prop('class', ' input-text admin__control-text required-entry _required');
                                        $('#myform_stateid').prop('class', 'select admin__control-select');
                                        $('#myform_is_custom_state').val('1');
                                    } else {
                                        $('.field-stateid').show().prop('class', 'admin__field field field-stateid  with-addon required _required');
                                        $('#myform_stateid').prop('class', 'required-entry _required select admin__control-select');
                                        $('#myform_custom_state').prop('class', 'input-text admin__control-text');
                                        $('.field-custom_state').hide();
                                        $('#myform_is_custom_state').val('0');
                                    }
                                   $(data.value).each(function()
                                   {
                                       var option = $('<option />');
                                       option.attr('value', this.region_id).text(this.name);
                                       $('#myform_stateid').append(option);
                                   });
                                   
                                   if(result == 'onload') {
                                       $('#myform_stateid').val('$stateId');
                                       $('#myform_custom_state').val('$customState');
                                       $('#myform_countryid').val('$countryId');
                                   } 
                               }
                       });
                   }
               });
           </script>"
       );
  
       $this->setForm($form);
       return $this;
   }
   /**
    * @return string
    */
   protected function _toHtml()
   {
       if ($this->canShowTab()) {
           $this->initForm();
           return parent::_toHtml();
       } else {
           return '';
       }
   }
   /**
    * Prepare the layout.
    *
    * @return $this
    */
   // You can call other Block also by using this function if you want to add phtml file.
   public function getFormHtml()
   {
      // get the current form as html content.
       $html = parent::getFormHtml();
       //Append the phtml file after the form content.
       $html .= $this->setTemplate('Vendorname_Customer::tab/customer_view.phtml')->toHtml();
       return $html;
   }
 
}