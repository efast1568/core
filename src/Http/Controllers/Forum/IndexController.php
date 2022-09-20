<?php

namespace Waterhole\Http\Controllers\Forum;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Waterhole\Feed\PostFeed;
use Waterhole\Filters\Following;
use Waterhole\Filters\Ignoring;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Page;

use function Waterhole\resolve_all;

/**
 * Controller for the forum index (home, channels, and pages).
 */
class IndexController extends Controller
{
    public function home(Request $request)
    {
        // Hide posts that the user has ignored, and posts that are in channels
        // that the user has ignored, to ensure the Home post feed is clean and
        // relevant. Also hide posts from "sandboxed" channels.
        $scope = function (Builder $query) {
            $query->withGlobalScope(Ignoring::EXCLUDE_IGNORED_SCOPE, function ($query) {
                $query->whereNot->ignoring();
            });

            $query->whereHas('channel', function ($query) {
                $query->where('sandbox', false);
                $query->whereNot->ignoring();
            });
        };

        $feed = new PostFeed(
            request: $request,
            filters: $this->addFilters(resolve_all(config('waterhole.forum.post_filters', []))),
            defaultLayout: config('waterhole.forum.default_post_layout'),
            scope: $scope,
        );

        return view('waterhole::forum.home')->with(compact('feed'));
    }

    public function channel(Channel $channel, Request $request)
    {
        $feed = new PostFeed(
            request: $request,
            filters: $this->addFilters(
                resolve_all($channel->filters ?: config('waterhole.forum.post_filters', [])),
            ),
            defaultLayout: $channel->default_layout ?:
            config('waterhole.forum.default_post_layout'),
            scope: function (Builder $query) use ($channel) {
                $query->where('posts.channel_id', $channel->id);
            },
        );

        return view('waterhole::forum.channel', compact('channel', 'feed'));
    }

    public function page(Page $page)
    {
        return view('waterhole::forum.page', compact('page'));
    }

    private function addFilters(array $filters)
    {
        if (Auth::user()) {
            $filters[] = new Following();
            $filters[] = new Ignoring();
        }

        return $filters;
    }
}