<?php
namespace AppZz\WP\Plugins;
use AppZz\Helpers\Arr;

class Metaboxes {

    private $_id;
    private $_class;
    private $_inside_class;
    private $_screen;

    public function __construct (array $params = array ())
    {
        $this->_id           = Arr::get ($params, 'id', 'poststuff');
        $this->_class        = Arr::get ($params, 'class', 'appzz-custom-metaboxes');
        $this->_inside_class = Arr::get ($params, 'inside_class', 'metabox-content');
        $this->_screen       = get_current_screen ();
        $this->_screen       = $this->_screen->id;
    }

    public static function footer ()
    {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('div[data-appzz-cust-mboxes="1"] .handlediv').click(function(ev) {
                ev.preventDefault();
                $(this).closest('.postbox').toggleClass('closed');
                $(this).attr('aria-expanded', function(_, attr) { return !(attr == 'true') })
            })
        });
        </script>
        <?php
    }

    public function add_meta_box ($id, $title, $content, array $params = array ())
    {
        $context = Arr::get ($params, 'context', 'normal');
        $priority = Arr::get ($params, 'context', 'default');
        $class = Arr::get ($params, 'class', '');

        if ( ! in_array ($context, array ('normal', 'side', 'advanced'))) {
            $context = 'normal';
        }

        if ( ! in_array ($priority, array ('default', 'high', 'low'))) {
            $priority = 'default';
        }

        add_meta_box ($id, $title, array (&$this, 'render_meta_box'), $this->_screen, $context, $priority, array ('content'=>$content));

        add_filter("postbox_classes_{$this->_screen}_{$id}", function ($classes) use ($class) {
            if ( ! empty ($class)) :
                $classes[] = $class;
            endif;
            return $classes;
        });
    }

    public function render_meta_box ($call, array $params = array ())
    {
        echo sprintf ('<div class="%s">%s</div>', $this->_inside_class, Arr::path ($params, 'args.content'));
    }

    public function render ()
    {
        $html = sprintf ('<div data-appzz-cust-mboxes="1" class="%s" id="%s">', $this->_class, $this->_id);
        ob_start();
        do_meta_boxes($this->_screen, 'normal', null);
        $html .= ob_get_contents();
        ob_get_clean();
        $html .= '</div>';
        return $html;
    }
}
