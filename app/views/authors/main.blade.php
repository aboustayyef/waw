@include('partials.header')
@include('partials.sidebar')
@include('partials.topbar')

<div id="content">
    <div class="posts cards blogger"> <!-- cards is default -->
	      @include('posts.render', array('posts', $posts))
    </div>
</div>

@include('partials.footer')
