unchi
=====

A little file hosting script with an image gallery. ShareX JSON file included.

I'm still working on getting it to be "amazing." At the moment it's in a sort of clusterfuck mode to me, but then again I'm really unconfident in my code so it could be good and i'm just bad.

I haven't even tested this outside of the existing installation I've used to make it. I'm not even sure if this works. I'm not even sure why I githubbed this. 

|       Server Requirements      | Client Requirements |
| ------------------------------ | ------------------- |
| PHP 5.1                        | Nothing             |
| Apache + RewriteRule (for now) | ShareX (optional)   |
| ~512mb RAM                     | Modern-ish browser  |

Features
--------
- Gallery with thumbnails
- Ability to migrate existing images and create new thumbnails
- Regular old file listing view
- No depencencies
- Will actually exist on my github
- JSON file for usage with ShareX
- Name means poop in japanese

Cons
----
- No BMP or TIFF thumbnail support
- Made by me
- Requires apache + mod_rewrite for the image urls
