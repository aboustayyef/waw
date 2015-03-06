<div id="searchResults" class="post_wrapper">
  <div class="card no_min"><?php $term = Input::get('q') ?>
    <h2>Search Results for "{{$term}}"</h2>
    <p>Here are the posts containing <span class="highlight">"{{$term}}"</span>. The most recent posts are on top</p>
  </div>
</div>
