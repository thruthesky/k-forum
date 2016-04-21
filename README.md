# K-Forum

K-Forum is short for "Knowledge Forum".

# todo

* Version compatibility from 4.3.3 to 4.5
    * Make it from 3.0 to 4.5

* SEO
	* keywords, images, alt, slug, length of tile/comment, H1/H2, keyword in title/H1/H2, links in content, etc.

* Comment work
	* Comment list. ( need to create a whole comment function to display buttons )
	* Comment Create with file upload. ( need to create a whole comment function ) 
	* Comment Update with file upload. 

* File Uploading.

    - show images in media page properly.

* Post/Comment Voting.

* Forum management
	* forum category statistics
	* forum options
		* how many post will be listed in list view page.
		* templating for each forum category.
		* and other options.
        



# Features

* K-Forum uses the same idea of managing posts and comments exactly as how WordPress does. Which means even if you uninstall K-Forum plugin, you can still use the contents that were written by K-Forum.
* K-Forum lets theme developers to use k-forum template files.  



 

## K Forum Delete

When a k-forum category is deleted, it renames the category as "Deleted-[Original Forum Name]" and puts it out of the k-forum category.

We do it just because the user may delete a category mistakenly. If the user wants to delete the categor, he can delete it from the "Post" ==> "Category Menu".


## K Forum URL

* /fourm/qna ==> qna forum.
* /forum/qna/edit ==> new post form page under qna forum
* /qna ==> qna fourm. This will be redirected to /forum/qna. If bbPress has same slug, bbPress will take priority. So, bbPress qna forum will be display.


* 만약 글을 k forum 에서 작성했는데, Post 메뉴에서 글을 수정 할 때, 카테고리를 여러개 선택한다면,

    - 글 하나에 여러개의 카테고리가 선택된다.
    - 하지만 K forum 은 글 하나에 하나의 k forum 카테고리만 가지는 것으로 가정하기 때문에 올바르지 않은 결과가 나타날 수 있다.
    - 따라서 글 수정은 Post 메뉴에서 하지 않는다.



# K-Forum Internals

## Much the same as WordPress

For instance, if a k-forum comment is deleted, it saves into trash just like WordPress comment does.




# ETC

* 글 카테고리 메뉴에서 K-forum 을 삭제해도 다시 생성된다.
