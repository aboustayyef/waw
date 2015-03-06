<?php

class StaticPagesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($slug = null)
	{
		if (empty($slug)) {
      return View::make('static.about', ['slug'  => $slug]);
    } else {
      if (in_array($slug, ['faq','submit', 'feedback', 'badge'])) {
         return View::make('static.' . $slug, ['slug'  => $slug]);
       } else {
        return Redirect::to('/posts/all');
       }
    }
	}

  public function submit($slug = null)
  {
    if (in_array($slug, ['submit', 'feedback'])) {
      if ($slug == 'submit') {
        $rulesSubmit = [
          'url' =>  'required',
          'email' =>  'required|email',
          'twitter' =>  'required|regex:#^@[A-Za-z0-9_]{1,15}$#'
        ];

        $validator = Validator::make(Input::all(), $rulesSubmit);
        if ($validator->fails()) {
          Input::flash();
          return View::make('static.submit', ['slug'  =>  'submit'])->withErrors($validator);
        }

        // if everything is okay

        $data = ['twitter' => Input::get('twitter'), 'email'=>Input::get('email'), 'url'  =>  Input::get('url')];

        Mail::queue('emails.submission', $data, function($message)
        {
            $message->from('donotreply@lebaneseblogs.com', 'Lebanese Blogs');
            $message->to('mustapha.hamoui@gmail.com', 'Mustapha Hamoui')->subject('[ Blog Submission ]');
        });

        // Mail::later(3,'emails.thankyouforsubmitting', $data, function($message)
        // {
        //     $email = Input::get('email');
        //     $message->from('donotreply@lebaneseblogs.com', 'Lebanese Blogs');
        //     $message->to($email)->subject('Thank You for Submitting your blog');
        // });

        Session::flash('message', 'Your blog has been submitted. Please permit a few days to process it.');
        return View::make('static.submit', ['slug'  =>  'submit']);
      }elseif ($slug == 'feedback'){
        $rulesFeedback = [
        'email' =>  'email',
        'feedback'  => 'required|min:10'
        ];
        $validator = Validator::make(Input::all(), $rulesFeedback);
        if ($validator->fails()) {
          Input::flash();
          return View::make('static.feedback', ['slug'  =>  'feedback'])->withErrors($validator);
        }

        // if everything is okay

        $data = ['email' => Input::get('email'), 'feedback'=>Input::get('feedback')];

        Mail::queue('emails.feedback', $data, function($message)
        {
            $message->from('donotreply@lebaneseblogs.com', 'Lebanese Blogs');
            $message->to('mustapha.hamoui@gmail.com', 'Mustapha Hamoui')->subject('[ Feedback for Lebanese Blogs ]');
        });

        // Mail::later(3,'emails.thankyouforsubmitting', $data, function($message)
        // {
        //     $email = Input::get('email');
        //     $message->from('donotreply@lebaneseblogs.com', 'Lebanese Blogs');
        //     $message->to($email)->subject('Thank You for Submitting your blog');
        // });
        //
        Session::flash('message', 'Your Feedback has been submitted. If you included your email address we\'ll do our best to get back to you');
        return View::make('static.feedback', ['slug'  =>  'submit']);
      }

      // if neither submit nor feedback
     } else {
      return Redirect::to('/posts/all');
     }
  }



}
