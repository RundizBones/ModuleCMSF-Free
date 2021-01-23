<?php
/** 
 * @license http://opensource.org/licenses/MIT MIT
 */


/**
 * Render taxonomy term data nested list such as categories.
 * 
 * @link https://stackoverflow.com/a/10309121/128761 Original source code.
 * @param array $items The taxonomy term data items.
 * @param \Rdb\System\Libraries\Url $Url The URL class.
 * @param string $currentUrl Current URL for check and set active.
 * @param bool $first Set to `true` to mark as first loop.
 * @return string Return rendered HTML list without first `ul` to use inside list menu item.
 */
function renderNestedList(array $items, \Rdb\System\Libraries\Url $Url, string $currentUrl = '', bool $first = true)
{
    if (true === $first) {
        $output = '<!-- begins render list -->' . PHP_EOL;
    } else {
        $output = '<ul class="dropdown-menu">' . PHP_EOL;
    }

    foreach ($items as $item) {
        if (!empty($item->alias_url_encoded)) {
            $linkUrl = $Url->getAppBasedPath(true) . '/' . $item->alias_url_encoded;
        } else {
            $linkUrl = $Url->getAppBasedPath(true) . '/taxonomies/' . rawurlencode($item->t_type) . '/' . $item->tid;
        }

        $output .= '<li class="nav-item';
        if (is_object($item) && property_exists($item, 'children') && !empty($item->children)) {
            $hasChildren = true;
            $output .= ' dropdown';
        }
        if ($currentUrl === $linkUrl) {
            $output .= ' active';
        }
        $output .= '"';// close double quote of class="..."
        $output .= '>';// close <li> tag.

        // <a link ----------------------------------------------------
        $output .= '<a class="nav-link';
        if (isset($hasChildren) && true === $hasChildren) {
            $output .= ' dropdown-toggle';
        }
        $output .= '"';// close double quote of <a class="..."

        $output .= ' href="' . $linkUrl . '"';

        if (isset($hasChildren) && true === $hasChildren) {
            $output .= ' role="button" data-toggle="dropdown" aria-expanded="false"';
        }

        $output .= '>';// close <a> tag.
        $output .= $item->t_name;
        $output .= '</a>' . PHP_EOL;
        // end <a link ----------------------------------------------------

        if (isset($hasChildren) && true === $hasChildren) {
            $output .= renderNestedList($item->children, $Url, $currentUrl, false);
        }

        $output .= '</li>' . PHP_EOL;

        unset($hasChildren, $linkUrl);
    }// endforeach;

    if (isset($item) && property_exists($item, 't_level') && 1 != $item->t_level) {
        $output .= '</ul>' . PHP_EOL;
    } else {
        $output .= '<!-- end render list -->' . PHP_EOL;
    }

    return $output;
}// renderNestedList