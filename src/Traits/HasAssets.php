<?php

namespace OpenDeveloper\Developer\Traits;

trait HasAssets
{
    /**
     * @var array
     */
    public static $script = [];

    /**
     * @var array
     */
    public static $deferredScript = [];

    /**
     * @var array
     */
    public static $style = [];

    /**
     * @var array
     */
    public static $css = [];

    /**
     * @var array
     */
    public static $js = [];

    /**
     * @var array
     */
    public static $html = [];

    /**
     * @var array
     */
    public static $headerJs = [];

    /**
     * @var string
     */
    public static $manifest = 'vendor/open-developer/minify-manifest.json';

    /**
     * @var array
     */
    public static $manifestData = [];

    /**
     * @var array
     */
    public static $min = [
        'js'  => 'vendor/open-developer/open-developer.min.js',
        'css' => 'vendor/open-developer/open-developer.min.css',
    ];

    /**
     * @var array
     */
    public static $baseCss = [
        // first libraries
        'vendor/open-developer/nprogress/nprogress.css',
        'vendor/open-developer/sweetalert2/sweetalert2.min.css',
        'vendor/open-developer/toastify-js/toastify.css',
        'vendor/open-developer/flatpickr/flatpicker-custom.css',
        'vendor/open-developer/choicesjs/styles/choices.min.css',
        'vendor/open-developer/sortablejs/nestable.css',

        // custom open developer stuff
        // generated through sass
        'vendor/open-developer/open-developer/css/styles.css',
    ];

    /**
     * @var array
     */
    public static $baseJs = [
        'vendor/open-developer/bootstrap5/bootstrap.bundle.min.js',
        'vendor/open-developer/nprogress/nprogress.js',
        'vendor/open-developer/axios/axios.min.js',
        'vendor/open-developer/sweetalert2/sweetalert2.min.js',
        'vendor/open-developer/toastify-js/toastify.js',
        'vendor/open-developer/flatpickr/flatpickr.min.js',
        'vendor/open-developer/choicesjs/scripts/choices.min.js',
        'vendor/open-developer/sortablejs/Sortable.min.js',

        'vendor/open-developer/open-developer/js/polyfills.js',
        'vendor/open-developer/open-developer/js/helpers.js',
        'vendor/open-developer/open-developer/js/open-developer.js',
        'vendor/open-developer/open-developer/js/open-developer-actions.js',
        'vendor/open-developer/open-developer/js/open-developer-grid.js',
        'vendor/open-developer/open-developer/js/open-developer-grid-inline-edit.js',
        'vendor/open-developer/open-developer/js/open-developer-form.js',
        'vendor/open-developer/open-developer/js/open-developer-toastr.js',
        'vendor/open-developer/open-developer/js/open-developer-resource.js',
        'vendor/open-developer/open-developer/js/open-developer-tree.js',
        'vendor/open-developer/open-developer/js/open-developer-selectable.js',

    ];

    /**
     * @var array
     */
    public static $minifyIgnoresCss = [];
    public static $minifyIgnoresJs = [];

    /**
     * Add css or get all css.
     *
     * @param null $css
     * @param bool $minify
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function css($css = null, $minify = true)
    {
        static::ignoreMinify('css', $css, $minify);

        if (!is_null($css)) {
            return self::$css = array_merge(self::$css, (array) $css);
        }

        if (!$css = static::getMinifiedCss()) {
            $css = array_merge(static::$css, static::baseCss());
        }

        $css = array_merge($css, static::$minifyIgnoresCss); // add minified ignored files
        $css = array_filter(array_unique($css));

        return view('developer::partials.css', compact('css'));
    }

    /**
     * @param null $css
     * @param bool $minify
     *
     * @return array|null
     */
    public static function baseCss($css = null, $minify = true)
    {
        static::ignoreMinify('css', $css, $minify);

        if (!is_null($css)) {
            return static::$baseCss = $css;
        }

        $skin = config('developer.skin', 'skin-blue-light');
        //array_unshift(static::$baseCss, "vendor/open-developer/DeveloperLTE/dist/css/skins/{$skin}.min.css");

        return static::$baseCss;
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     * @param bool $minify
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function js($js = null, $minify = true)
    {
        static::ignoreMinify('js', $js, $minify);

        if (!is_null($js)) {
            return self::$js = array_merge(self::$js, (array) $js);
        }

        if (!$js = static::getMinifiedJs()) {
            $js = array_merge(static::baseJs(), static::$js);
        }

        $js = array_merge($js, static::$minifyIgnoresJs); // add minified ignored files
        $js = array_filter(array_unique($js));

        return view('developer::partials.js', compact('js'));
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function headerJs($js = null)
    {
        if (!is_null($js)) {
            return self::$headerJs = array_merge(self::$headerJs, (array) $js);
        }

        return view('developer::partials.js', ['js' => array_unique(static::$headerJs)]);
    }

    /**
     * @param null $js
     * @param bool $minify
     *
     * @return array|null
     */
    public static function baseJs($js = null, $minify = true)
    {
        static::ignoreMinify('js', $js, $minify);

        if (!is_null($js)) {
            return static::$baseJs = $js;
        }

        return static::$baseJs;
    }

    /**
     * @param string $assets
     * @param bool   $ignore
     */
    public static function ignoreMinify($type, $assets, $ignore = true)
    {
        if (!$ignore) {
            if ($type == 'css') {
                static::$minifyIgnoresCss[] = $assets;
            } else {
                static::$minifyIgnoresJs[] = $assets;
            }
        }
    }

    /**
     * @param string $script
     * @param bool   $deferred
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function script($script = '', $deferred = false)
    {
        if (!empty($script)) {
            if ($deferred) {
                return self::$deferredScript = array_merge(self::$deferredScript, (array) $script);
            }

            return self::$script = array_merge(self::$script, (array) $script);
        }

        $script = collect(static::$script)
            ->merge(static::$deferredScript)
            ->unique()
            ->map(function ($line) {
                return $line;
                //@see https://stackoverflow.com/questions/19509863/how-to-remove-js-comments-using-php
                $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
                $line = preg_replace($pattern, '', $line);

                return preg_replace('/\s+/', ' ', $line);
            });

        return view('developer::partials.script', compact('script'));
    }

    /**
     * @param string $style
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function style($style = '')
    {
        if (!empty($style)) {
            return self::$style = array_merge(self::$style, (array) $style);
        }

        $style = collect(static::$style)
            ->unique()
            ->map(function ($line) {
                return preg_replace('/\s+/', ' ', $line);
            });

        return view('developer::partials.style', compact('style'));
    }

    /**
     * @param string $html
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function html($html = '')
    {
        if (!empty($html)) {
            return self::$html = array_merge(self::$html, (array) $html);
        }

        return view('developer::partials.html', ['html' => array_unique(self::$html)]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected static function getManifestData($key)
    {
        if (!empty(static::$manifestData)) {
            return static::$manifestData[$key];
        }

        static::$manifestData = json_decode(
            file_get_contents(public_path(static::$manifest)),
            true
        );

        return static::$manifestData[$key];
    }

    /**
     * @return bool|mixed
     */
    protected static function getMinifiedCss()
    {
        if (!config('developer.minify_assets') || !file_exists(public_path(static::$manifest))) {
            return false;
        }

        return static::getManifestData('css');
    }

    /**
     * @return bool|mixed
     */
    protected static function getMinifiedJs()
    {
        if (!config('developer.minify_assets') || !file_exists(public_path(static::$manifest))) {
            return false;
        }

        return static::getManifestData('js');
    }

    /**
     * @param $component
     */
    public static function component($component, $data = [])
    {
        $string = view($component, $data)->render();

        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$string);
        libxml_use_internal_errors(false);

        if ($head = $dom->getElementsByTagName('head')->item(0)) {
            foreach ($head->childNodes as $child) {
                if ($child instanceof \DOMElement) {
                    if ($child->tagName == 'style' && !empty($child->nodeValue)) {
                        static::style($child->nodeValue);
                        continue;
                    }

                    if ($child->tagName == 'link' && $child->hasAttribute('href')) {
                        static::css($child->getAttribute('href'));
                    }

                    if ($child->tagName == 'script') {
                        if ($child->hasAttribute('src')) {
                            static::js($child->getAttribute('src'));
                        } else {
                            static::script(';(function () {'.$child->nodeValue.'})();');
                        }

                        continue;
                    }
                }
            }
        }

        $render = '';

        if ($body = $dom->getElementsByTagName('body')->item(0)) {
            foreach ($body->childNodes as $child) {
                if ($child instanceof \DOMElement) {
                    if ($child->tagName == 'style' && !empty($child->nodeValue)) {
                        static::style($child->nodeValue);
                        continue;
                    }

                    if ($child->tagName == 'script' && !empty($child->nodeValue)) {
                        static::script(';(function () {'.$child->nodeValue.'})();');
                        continue;
                    }

                    if ($child->tagName == 'template') {
                        if ($child->getAttribute('render') == 'true') {
                            // this will render the template tags right into the dom. Don't think we want this
                            $html = '';
                            foreach ($child->childNodes as $childNode) {
                                $html .= $child->ownerDocument->saveHTML($childNode);
                            }
                        } else {
                            // this leaves the template tags in place, so they won't get rendered right away
                            $sub_doc = new \DOMDocument();
                            $sub_doc->appendChild($sub_doc->importNode($child, true));
                            $html = $sub_doc->saveHTML();
                        }
                        $html && static::html($html);

                        continue;
                    }
                }

                $render .= $body->ownerDocument->saveHTML($child);
            }
        }

        return trim($render);
    }
}
