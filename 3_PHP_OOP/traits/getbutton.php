<?php
trait GetButton {
    protected $btnMsg;
    protected $btnPage;
    protected $btnClass;
    
    protected function renderGetButton(): void {
        HtmlBuilder::openLink($this->btnPage, $this->btnClass ?? '');
        echo $this->btnMsg ?? '';
        HtmlBuilder::closeLink();
    }
}
?>