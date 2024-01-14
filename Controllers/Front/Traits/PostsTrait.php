<?php
/**
 * Posts trait.
 * 
 * Working about posts listing.
 * 
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front\Traits;


/**
 *
 * @author mr.v
 */
trait PostsTrait
{


    /**
     * List posts that is published.
     * 
     * @param string $tid Taxonomy ID.
     * @return array
     */
    protected function listPublishedPosts(string $tid = ''): array
    {
        $output = [];
        $postsPerPage = 10;
        $currentOffset = (int) trim($this->Input->get('offset', 0));
        $Url = new \Rdb\System\Libraries\Url($this->Container);

        $PostsDb = new \Rdb\Modules\RdbCMSA\Models\PostsDb($this->Container);
        $options = [];
        $options['where'] = [
            'posts.language' => ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? 'th'),
        ];
        if (!empty($tid)) {
            $options['tidsIn'] = [$tid];
        }
        $options['isPublished'] = true;
        $options['sortOrders'] = [
            ['sort' => 'post_publish_date', 'order' => 'desc'],
        ];
        $options['limit'] = $postsPerPage;
        $options['offset'] = $currentOffset;
        $options['skipCategories'] = true;
        $options['skipTags'] = true;
        $listPosts = $PostsDb->listItems($options);
        unset($options, $PostsDb);
        $output['listPosts'] = ($listPosts['items'] ?? []);

        $totalPosts = $listPosts['total'];
        if ($totalPosts !== 0 && $postsPerPage !== 0) {
            $totalPages = ceil($totalPosts / $postsPerPage);
        } else {
            $totalPages = 0;
        }
        $previousOffset = max(0, ($currentOffset - 1));
        $nextOffset = min($totalPages, ($currentOffset + 1));
        $output['paginations'] = [
            'previous' => $Url->getCurrentUrl(true) . '?offset=' . $previousOffset,
            'next' => $Url->getCurrentUrl(true) . '?offset=' . $nextOffset,
        ];
        unset($currentOffset, $listPosts, $nextOffset, $postsPerPage, $previousOffset, $totalPages, $totalPosts, $Url);
        
        return $output;
    }// listPublishedPosts


}
