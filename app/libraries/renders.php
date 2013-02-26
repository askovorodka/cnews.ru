<?php 
/**
 * Генерация результатов обработки
 * @author ashmits by 30.01.2013 13:59
 *
 */
class Renders
{

	private $render_result = null;
	private $CI = null;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model(array('model_common','model_reviews'));
		$this->CI->load->library(array('reviews_views','validate'));
		$this->CI->load->helper(array('image_helper'));
	}
	
	/**
	 * Достает все статьи обзора и рендерит шаблон
	 * @param unknown_type $reviews_id
	 */
	public function set_reviews_articles_renders($reviews_id)
	{
		$articles = $this->CI->model_reviews->get_reviews_articles_by_review_id((int)$reviews_id, 1);
		if (count($articles) > 0)
		{
			foreach ($articles as $article)
			{
				$this->set_reviews_articles(intval($article['id']));
			}
		}
	}
	
	public function set_reviews_articles($article_id)
	{
		
		$article = $this->CI->validate->validate_articles_by_id((int)$article_id, false);
		$reviews = $this->CI->validate->validate_reviews_by_id((int)$article['reviews_id'], false);
		$article['text'] = Common_Helper::get_tables_by_tags((string)$article['text']);
		$article['text'] = Image_Helper::set_images_in_text((string)$article['text']);
		$this->CI->smarty->assign('article', $article);
		$this->CI->smarty->assign('reviews', $reviews);
		
		$render_result = $this->CI->smarty->fetch('front/reviews/articles_preview');
		
		$this->CI->model_common->update("reviews_articles", array("article_render_result" => $render_result), array("id" => intval($article_id)));
		
	}
	
	/**
	 * Достает все интервью обзора и рендерит шаблон
	 * @param unknown_type $reviews_id
	 */
	public function set_reviews_interviews_renders($reviews_id)
	{

		$interviews = $this->CI->model_reviews->get_reviews_interviews_by_review_id((int)$reviews_id, 1);
		
		if (count($interviews) > 0)
		{
			foreach ($interviews as $interview)
			{
				$this->set_reviews_interviews((int)$interview['id']);
			}
		}
		
	}
	
	public function set_reviews_interviews($interview_id)
	{
		
		$interview = $this->CI->validate->validate_interview_by_id(intval($interview_id), false);
		$reviews = $this->CI->validate->validate_reviews_by_id(intval($interview['reviews_id']), false);
		$interview['text'] = Common_Helper::get_tables_by_tags($interview['text']);
		$interview['text'] = Image_Helper::set_images_in_text($interview['text']);
		$this->CI->smarty->assign('interview', $interview);
		$this->CI->smarty->assign('reviews', $reviews);
		
		$render_result = $this->CI->smarty->fetch('front/reviews/interview_preview');
		
		$this->CI->model_common->update("reviews_interviews", array("interview_render_result" => $render_result), array("id" => intval($interview_id)));
		
	}

	/**
	 * Достает все кейсы обзора и рендерит шаблон
	 * @param unknown_type $reviews_id
	 */
	public function set_reviews_cases_renders($reviews_id)
	{
		
		$cases = $this->CI->model_reviews->get_reviews_cases_by_review_id((int)$reviews_id, 1);

		if (count($cases) > 0)
		{
			foreach ($cases as $case)
			{
				$this->set_reviews_cases($case['id']);
			}
		}
		
	}
	
	public function set_reviews_cases($case_id)
	{
	
		$case = $this->CI->validate->validate_case_by_id(intval($case_id), false);
		$case['text'] = Common_Helper::get_tables_by_tags((string)$case['text']);
		$case['text'] = Image_Helper::set_images_in_text((string)$case['text']);
		
		$this->CI->smarty->assign('case', $case);
		$render_result = $this->CI->smarty->fetch('front/reviews/case_preview');
		
		$this->CI->model_common->update("reviews_cases", array("case_render_result" => $render_result), array("id" => intval($case_id)));
		//echo $this->CI->model_common->last_query();
	}
	
}

?>