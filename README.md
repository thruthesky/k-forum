# K-Forum

K-Forum is short for "Knowledge Forum".

# Features

* K-Forum uses the same idea of managing posts and comments exactly as how WordPress does. Which means even if you uninstall K-Forum plugin, you can still use the contents that were written by K-Forum.
* K-Forum lets theme developers to use k-forum template files.  


## K Forum Delete

When a k-forum category is deleted, it renames the category as "Deleted-[Original Forum Name]" and puts it out of the k-forum category.

We do it just because the user may delete a category mistakenly. If the user wants to delete the categor, he can delete it from the "Post" ==> "Category Menu".



 

# todo


* File Uploading

    - update. update and delete file.

    - show images in media page properly.

    - upload/edit files on comments.


* Forum management

    - forum category CRUD
    - forum category statistics
    - forum options
        -- how many post will be listed in list view page.
        -- templating for each forum category.
        -- and other options.
        

* Forum ID Routing

    - http://abc.com/qna will turn into "http://abc.com/category/forum/qna"

        -- when forum configuration has edited, option 'forum-routing' will be updated with all k-forum ID.

        -- k forum ID will be saved and matched on every acess.

            -- if matched, then rewrite it into the category.


* 만약 글을 k forum 에서 작성했는데, Post 메뉴에서 글을 수정 할 때, 카테고리를 여러개 선택한다면,

    - 글 하나에 여러개의 카테고리가 선택된다.
    - 하지만 K forum 은 글 하나에 하나의 k forum 카테고리만 가지는 것으로 가정하기 때문에 올바르지 않은 결과가 나타날 수 있다.
    - 따라서 글 수정은 Post 메뉴에서 하지 않는다.

