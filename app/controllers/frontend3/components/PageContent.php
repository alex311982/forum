<?php

class PageContent extends Component
{
	public function executeRender($vars)
	{
        foreach($vars as $name => &$val)
        {
            $$name = $val;
        }
        unset($vars);

        ob_start();
        include(CORE_PTH_TPL.$tpl);
        $content = ob_get_clean();
		
		unset($vars);
		print($content);
		return true;
	}
}