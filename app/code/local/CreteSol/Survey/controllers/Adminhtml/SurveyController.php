<?php
/**
 * Upload Survey CSV just read the csv and according to the fields save data in db.
 * Upload CSV and Save data
 * 
 * */
class CreteSol_Survey_Adminhtml_SurveyController extends
    Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed(){
        return true;
    }

    public function indexAction()
    {

        $this->loadLayout(); //->_setActiveMenu('survey')->_title($this->__('Survey'));
        //$this->_addContent($this->getLayout()->createBlock('survey/adminhtml_survey_grid'));
        $this->renderLayout();

    }
    public function uploadCsvSurveyAction()
    {

        $this->loadLayout();
        $this->renderLayout();
    }
/**
 * @function Save  data to survey table get data from csv and save it.
 * 
 * 
 * */
    public function saveDataAction()
    {
        if($this->getRequest()->getParam('csv_type') == 2){
            
            $this->typeFormSurvery();
            
            
            
        }else{
            
                    try
                    {
                    $path = Mage::getBaseDir() . DS . 'survey_csv';
                    if (!is_dir())
                        mkdir($path);
                    $uploader = new Varien_File_Uploader('csv_file');
                    $uploader->setAllowedExtensions(array('csv'));
                    $uploader->setAllowCreateFolders(true);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $savedPath = $uploader->save($path, $fname);
                    $savedFilePath = $savedPath['path'] . DS . $savedPath['file'];
                    $csv = new Varien_File_Csv();
                    $data = $csv->getData($savedFilePath);
                    unlink($savedFilePath);
        
                    $surveryModel = Mage::getModel('survey/survey');
                    $hasColumnNameRow = $surveryModel->getCollection()->setPageSize(1);
        
                    if (count($hasColumnNameRow) == 0)
                    {
        
        
                        $surveryModel->setData('completed', strtotime(trim($data[0][1])));
                        $surveryModel->setData('token', trim($data[0][4]));
                        $surveryModel->setData('q1', $data[0][9]);
        
                        $surveryModel->setData('q2', $data[0][10]);
        
                        $surveryModel->setData('q3', $data[0][11]);
        
                        $surveryModel->setData('q4', $data[0][12]);
        
                        $surveryModel->setData('q5', $data[0][13]);
        
                        $surveryModel->setData('q6', $data[0][14]);
        
                        $surveryModel->setData('q7', $data[0][15]);
        
                        $surveryModel->setData('q8', $data[0][16]);
        
                        $surveryModel->setData('q9', $data[0][17]);
                        $surveryModel->save();
                        $surveryModel->unsetData();
        
                    }
        
        
                    unset($data[0]);
        
        
                    foreach ($data as $key => $value):
        
        
                        $surveryModel->setData('token', $value[4]);
        
                        $surveryModel->setData('completed', strtotime(trim($value[1])));
                        $surveryModel->setData('q1', $value[9]);
        
                        $surveryModel->setData('q2', $value[10]);
        
                        $surveryModel->setData('q3', $value[11]);
        
                        $surveryModel->setData('q4', $value[12]);
        
                        $surveryModel->setData('q5', $value[13]);
        
                        $surveryModel->setData('q6', $value[14]);
        
                        $surveryModel->setData('q7', $value[15]);
        
                        $surveryModel->setData('q8', $value[16]);
        
                        $surveryModel->setData('q9', $value[17]);
        
                        $surveryModel->save();
                        $surveryModel->unsetData();
        
                    endforeach;
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Survey  Uploaded Successfully.. '));
                    Mage::getSingleton('adminhtml/session')->setCrmData(false);
                    $this->_redirect('*/*/');
                    return;
        
                }
                catch (exception $e)
                {
                    Mage::getSingleton('adminhtml/session')->addError('There is some error.. ');
                    Mage::getSingleton('adminhtml/session')->setCrmData($this->getRequest()->
                        getPost());
                    $this->_redirect('*/*/');
                    return;
                }
                    
            
        }
        

    }
     public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('survey/adminhtml_survey_grid')->
            toHtml());
    }
/**
 * @function save type Form csv data
 * 
 * 
 * 
 * 
 * */

    public function typeFormSurvery(){
        try
                    {
                    $path = Mage::getBaseDir() . DS . 'survey_csv';
                    if (!is_dir())
                        mkdir($path);
                    $uploader = new Varien_File_Uploader('csv_file');
                    $uploader->setAllowedExtensions(array('csv'));
                    $uploader->setAllowCreateFolders(true);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(false);
                    $savedPath = $uploader->save($path, $fname);
                    $savedFilePath = $savedPath['path'] . DS . $savedPath['file'];
                    $csv = new Varien_File_Csv();
                    $data = $csv->getData($savedFilePath);
                    unlink($savedFilePath);
        
                    $surveryModel = Mage::getModel('survey/survey');
                    $hasColumnNameRow = $surveryModel->getCollection()->setPageSize(1);
                        //zend_debug::dump($data);exit;
                    if (count($hasColumnNameRow) == 0)
                    {
        
        
                        //$surveryModel->setData('completed', strtotime(trim($value[1])));
                        //$surveryModel->setData('token', trim($value[4]));
                        $surveryModel->setData('q1', $data[0][1]);
        
                        $surveryModel->setData('q2', $data[0][2]);
        
                       // $surveryModel->setData('q3', $data[0][11]);
        
                        $surveryModel->setData('q4', $data[0][3]);
        
                        $surveryModel->setData('q5', $data[0][4]);
        
                        $surveryModel->setData('q6', $data[0][7]);
        
                        $surveryModel->setData('q7', $data[0][6]);
        
                        $surveryModel->setData('q8', $data[0][5]);
        
                        //$surveryModel->setData('q9', $data[0][17]);
                        $surveryModel->save();
                        $surveryModel->unsetData();
        
                    }
        
                    unset($data[0]);
        
        
                    foreach ($data as $key => $value):
        
        
                        $surveryModel->setData('token', $value[9]);
                        $date = trim($value[12]);
                        $date = str_replace('/', '-', $date);
                        
                        $surveryModel->setData('completed', date('Y-m-d',strtotime($date)));
                        $surveryModel->setData('q1', $value[1]);
        
                        $surveryModel->setData('q2', $value[2]);
        
                        //$surveryModel->setData('q3', $value[11]);
        
                        $surveryModel->setData('q4', $value[3]);
        
                        $surveryModel->setData('q5', $value[4]);
        
                        $surveryModel->setData('q6', $value[7]);
        
                        $surveryModel->setData('q7', $value[6]);
        
                        $surveryModel->setData('q8', $value[5]);
        
                        //$surveryModel->setData('q9', $value[17]);
        
                        $surveryModel->save();
                        $surveryModel->unsetData();
        
                    endforeach;
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Survey  Uploaded Successfully.. '));
                    Mage::getSingleton('adminhtml/session')->setCrmData(false);
                    $this->_redirect('*/*/');
                    return;
        
                }
                catch (exception $e)
                {
                    Mage::getSingleton('adminhtml/session')->addError('There is some error.. ');
                    Mage::getSingleton('adminhtml/session')->setCrmData($this->getRequest()->
                        getPost());
                    $this->_redirect('*/*/');
                    return;
                }
    }
    /**
 * @function sync data to typeform server get data from API call and save to local magento 
 * 
 * 
 * */
    public function syncDataAction()
    {
        try
        {
            $_typeFormDateOption = Mage::getStoreConfig('crm/survey_group/date_options', Mage::app()->getStore()->getStoreId());
            $_typeFormSincetDate = Mage::getStoreConfig('crm/survey_group/manual_date_options', Mage::app()->getStore()->getStoreId());
            $_typeFormUid = Mage::getStoreConfig('crm/survey_group/typeform_uid', Mage::app()->getStore()->getStoreId());
            $_typeFormApikey = Mage::getStoreConfig('crm/survey_group/typeform_apikey', Mage::app()->getStore()->getStoreId());
            if($_typeFormDateOption == 'manual'){
                
                
                 $date = strtotime($_typeFormSincetDate);
             
                
            }else{
                echo $date = strtotime(date("Y-m-d"));
            }
           
            //$date = strtotime('2015-10-02'); //date('Y-m-d','2015-09-01'));//curl
            //$_url = " curl https://api.typeform.com/v0/form/UM4Odx?'key=73abac3de1618c4c4a8201eddcf20ea9bffaf1c7&completed=true&since={$date}'"; //.strtotime(date('Y-m-d','2015-10-05'));
            $_url = " curl https://api.typeform.com/v0/form/{$_typeFormUid}?'key={$_typeFormApikey}&completed=true&since={$date}'"; //.strtotime(date('Y-m-d','2015-10-05'));
           
           
            //$_testUrl = 'curl https://api.typeform.com/v0/form/wQmvVv?key=3338a8043263d5ffc77c55f077baa383bdcd96ce&completed=true';

            //$_testCmd = "curl -X GET https://api.typeform.io/latest/ -H X-API-TOKEN:5e66768eca45b9f1958a088bfe0333ca";
            $k = shell_exec($_url);
            //echo '<pre>';
            if($k){
           
            //print_r(json_decode($k));
            $k = json_decode($k);
            //exit;
            $_surveyQuestionArray = array();
            $_surveryAnswerArray = array();
            foreach ($k->questions as $key => $v)
            {
                //print_r($v);
                $_surveyQuestionArray[$v->id] = $v->question;
                // echo $v->id;
                //echo $v->question;


            }
            foreach ($k->responses as $k => $v)
            {


                $dateEx = explode(' ', $v->metadata->date_submit);

                $_dt = DateTime::createFromFormat('Y-m-d', $dateEx[0]);

                if ($_dt && $_dt->format('Y-m-d') == $dateEx[0])
                {
                    $_surveryAnswerArray[$v->id];
                    $_surveryAnswerArray[$v->id]['date_submit'] = $v->metadata->date_submit;
                    $_surveryAnswerArray[$v->id]['order_id'] = $v->hidden->orderid;
                    $_surveryAnswerArray[$v->id]['name'] = $v->hidden->name;
                    $_surveryAnswerArray[$v->id]['email'] = $v->hidden->email;
                    foreach ($v->answers as $y => $x)
                    {

                        $_surveryAnswerArray[$v->id][$y] = $x;
                    }
                }


            }

            $surveryModel = Mage::getModel('survey/survey');
            foreach ($_surveryAnswerArray as $key => $value):
                $surveryModel = Mage::getModel('survey/survey');
                    $collection = $surveryModel->getCollection()->addFieldToFilter('token',$value['order_id']);//->setPageSize(1);
                  
                    
                    
                   if($collection->getSize() <= 0) {
                    
                   

                $surveryModel->setData('token', $value['order_id']);
                $surveryModel->setData('completed', $value['date_submit']);//date('Y-m-d', strtotime($value[$key]['date_submit']))
                $surveryModel->setData('q1', $value['list_9931707_choice']);

                $surveryModel->setData('q2', $value['list_9931901_choice']);

                //$surveryModel->setData('q3', $value[11]);

                $surveryModel->setData('q4', $value['list_9931812_choice']);

                $surveryModel->setData('q5', $value['textfield_9931969']);

                $surveryModel->setData('q6', $value['textarea_9931937']);

                $surveryModel->setData('q7', $value['textfield_9932035']);

                $surveryModel->setData('q8', $value['textfield_9932027']);

                //$surveryModel->setData('q9', $value[17]);

                $surveryModel->save();
                $surveryModel->unsetData();
                }
            endforeach;
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('TypeForm Survey Data Synced Successfully.. '));
            Mage::getSingleton('adminhtml/session')->setCrmData(false);
            $this->_redirect('*/*/');
            return;
                 
            }

        }
        catch (exception $e)
        {
            Mage::getSingleton('adminhtml/session')->addError('There is some error.. ');
            Mage::getSingleton('adminhtml/session')->setCrmData($this->getRequest()->
                getPost());
            $this->_redirect('*/*/');
            return;
        }
    }
}