Remoteimage
====================

This extension adds a very simple twig function to fetch and save a remote file
and return it's new, path which can then be used in bolt's built in image
functions. It works very well with extensions that return data from social
media such as my own instagram, facebook and twitter embeds extensions.

**Warning: It does not check if the returned file is an image, or if it is in
any way harmful, in it's current state it is up to you to check this. PR's to
fix this are very welecome!**

##Examples:

    {% set image = remoteimage(
        url = 'https://www.example.com/example.jpg'
    ) %}