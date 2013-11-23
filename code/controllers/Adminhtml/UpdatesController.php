<?php

class CosmoCommerce_Updates_Adminhtml_UpdatesController extends Mage_Adminhtml_Controller_Action
{
    public function commitAction()
    {
        $postData = $this->getRequest()->getQuery();
        
        if(isset($postData['repo'])){
            $repo=$postData['repo'];
        }else{
            Mage::getSingleton('adminhtml/session')->addError('请选择正确模块');
            $this->_redirectReferer('*/*/');   
            return;
        }
        if(isset($postData['note'])){
            $note=$postData['note'];
        }else{
            Mage::getSingleton('adminhtml/session')->addError('请填写模块提交备注');
            $this->_redirectReferer('*/*/');   
            return;
        }
        if(isset($postData['user'])){
            $user=$postData['user'];
        }else{
            Mage::getSingleton('adminhtml/session')->addError('请填写模块提交用户名');
            $this->_redirectReferer('*/*/');   
            return;
        }
        if(isset($postData['pass'])){
            $pass=$postData['pass'];
        }else{
            Mage::getSingleton('adminhtml/session')->addError('请填写模块提交密码');
            $this->_redirectReferer('*/*/');   
            return;
        }
        
        
        $base_path = Mage::getBaseDir('base');
        $modman_path = Mage::getBaseDir('base').DS.'.modman';
        $mod_path = Mage::getBaseDir('base').DS.'.modman'.DS.$repo;
        
        if(file_exists($mod_path)){

            $config = new Git2\Config($mod_path."/.git/config");
            $remoteurl=( $config->get("remote")['origin']['url'] );
            
            
            $extract=(explode('https://',$remoteurl));
            
            chdir($mod_path);
            $last_line = exec(escapeshellcmd('git commit -am ').escapeshellarg($note), $output,$retval);
            
            $commiturl= "https://".$user.":".$pass."@".$extract[1];
            $last_line_commit = exec(escapeshellcmd('git push --repo ').escapeshellarg($commiturl), $output_commit,$retval_commit);
            
            
            //$last_line = exec('git commit -a -m "'.$note.'" ', $output,$retval);
           
            $message=implode("<br />",$output);
            $message.=implode("<br />",$output_commit);
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
            $this->_redirectReferer('*/*/');
        }else{
        
            Mage::getSingleton('adminhtml/session')->addError('模块不存在');
            $this->_redirectReferer('*/*/');   
            return;
        }
    }

    public function updateAction()
    {
        $postData = $this->getRequest()->getQuery();
        
        
        if(isset($postData['repo'])){
            $repo=$postData['repo'];
        }else{
            Mage::getSingleton('adminhtml/session')->addError('请选择正确模块');
            $this->_redirectReferer('*/*/');   
            return;
        }
        
        $base_path = Mage::getBaseDir('base');
        $modman_path = Mage::getBaseDir('base').DS.'.modman';
        
        
        
        
        if(file_exists($modman_path)){
        
            chdir($base_path);
            $last_line = exec(escapeshellcmd('/var/www/bin/modman update ').escapeshellarg($repo), $output,$retval);
            $message=implode("<br />",$output);
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
            $this->_redirectReferer('*/*/');
        }else{
        
            Mage::getSingleton('adminhtml/session')->addError('模块不存在');
            $this->_redirectReferer('*/*/');   
            return;
        }
    }

    public function updatefAction()
    {
        $postData = $this->getRequest()->getQuery();
        
        
        if(isset($postData['repo'])){
            $repo=$postData['repo'];
        }else{
            Mage::getSingleton('adminhtml/session')->addError('请选择正确模块');
            $this->_redirectReferer('*/*/');   
            return;
        }
        
        $base_path = Mage::getBaseDir('base');
        $modman_path = Mage::getBaseDir('base').DS.'.modman';
        if(file_exists($modman_path)){
        
            chdir($base_path);
            $last_line = exec(escapeshellcmd('/var/www/bin/modman update ').escapeshellarg($repo.' --force'), $output,$retval);
            $message=implode("<br />",$output);
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
            $this->_redirectReferer('*/*/');
        }else{
        
            Mage::getSingleton('adminhtml/session')->addError('模块不存在');
            $this->_redirectReferer('*/*/');   
            return;
        }
    }
}
