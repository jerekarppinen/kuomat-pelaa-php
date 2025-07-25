<?php
$rss_url = "https://anchor.fm/s/e698ed4c/podcast/rss";

function get_episodes($rss_url) {
    // Remove this line - you're overriding the parameter!
    // $rss_url = 'YOUR_RSS_URL_HERE'; 
    
    // Parse RSS feed
    $xml = simplexml_load_file($rss_url);
    
    if ($xml === false) {
        http_response_code(500);
        return json_encode(["error" => "Failed to parse RSS feed"]);
    }
    
    $items = $xml->channel->item;
    $total_episodes = count($items);
    $episodes = [];
    
    $i = 0;
    foreach ($items as $item) {
        $episode_number = $total_episodes - $i;
        $i++;
        $episode = Episode::from_rss_item($item, $episode_number);
        $episodes[] = $episode;
    }
    
    return json_encode(array_map(function($episode) {
        return $episode->to_dict();
    }, $episodes));
}

// Simple Episode class
class Episode {
    public $episode_number;
    public $title;
    public $description;
    public $pub_date;
    public $link;
    
    public static function from_rss_item($item, $episode_number) {
        $episode = new self();
        $episode->episode_number = $episode_number;
        $episode->title = (string)$item->title;
        $episode->description = (string)$item->description;
        $episode->pub_date = (string)$item->pubDate;
        $episode->link = (string)$item->link;
        return $episode;
    }
    
    public function to_dict() {
        return [
            'episode_number' => $this->episode_number,
            'title' => $this->title,
            'description' => $this->description,
            'pub_date' => $this->pub_date,
            'link' => $this->link
        ];
    }
}

// Add proper header for JSON output
header('Content-Type: application/json');

// Call the function and output result
echo get_episodes($rss_url);
?>