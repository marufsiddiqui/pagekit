@if (comments)
<table class="uk-table uk-table-hover">
    <thead>
        <tr>
            <th class="pk-table-width-minimum"><input type="checkbox" class="js-select-all"></th>
            <th class="pk-table-min-width-300" colspan="2">@trans('Comment')</th>
            <th class="pk-table-width-100 uk-text-center">@trans('Status')</th>
            <th class="pk-table-width-200">@trans('In response to')</th>
        </tr>
    </thead>
    <tbody>
        @foreach (comments as comment)
        <tr class="js-comment"
            data-id="@comment.id"
            data-author="@comment.author"
            data-email="@comment.email"
            data-url="@comment.url"
            data-content="@comment.content"
            data-user-id="@comment.userId"
            >
            <td>
                <input class="js-select pk-blog-comments-margin" type="checkbox" name="ids[]" value="@comment.id">
            </td>
            <td class="pk-table-width-minimum">
                @gravatar(comment.email, ['size' => 80, 'attrs' => ['width' => '40', 'height' => '40', 'alt' => comment.author, 'class' => 'uk-img-preserve uk-border-circle']])
            </td>
            <td>
                <div class="uk-margin uk-clearfix">
                    <div class="uk-float-left uk-width-large-1-2">
                        @comment.author
                        <br><a class="uk-link-reset uk-text-muted" href="mailto:@comment.email">@comment.email</a>
                    </div>
                    <div class="uk-float-left uk-width-large-1-2 pk-text-right-large">
                        @if (comment.thread.status == 2 && comment.thread.hasAccess(app.user))
                            <a href="@url.route('@blog/id', ['id' => comment.threadId])#comment-@comment.id">@comment.created|date('l, d-M-y H:i:s')</a>
                        @else
                            @comment.created|date('l, d-M-y H:i:s')
                        @endif
                    </div>
                </div>
                <div>@comment.content</div>
                <p>
                    <a href="#" data-quick-action="reply">@trans('Reply')</a>
                    <a href="#" data-quick-action="edit">@trans('Edit')</a>
                </p>
            </td>
            <td class="uk-text-center">
                @comment.statusText
            </td>
            <td>
                <a href="@url.route('@blog/post/edit', ['id' => comment.threadId])">@comment.thread.title</a>
                <a href="#" data-filter="post" data-value="@comment.threadId">(@comment.thread.numComments)</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif