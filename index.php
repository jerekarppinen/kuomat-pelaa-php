<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuomat Pelaa - Podcast</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    // RSS reader functions
    $rss_url = "https://anchor.fm/s/e698ed4c/podcast/rss";

    function get_episodes($rss_url) {
        $xml = simplexml_load_file($rss_url);
        
        if ($xml === false) {
            return [];
        }
        
        $items = $xml->channel->item;
        $total_episodes = count($items);
        $episodes = [];
        
        $i = 0;
        foreach ($items as $item) {
            $episode_number = $total_episodes - $i;
            $episode = Episode::from_rss_item($item, $episode_number);
            $episodes[] = $episode;
            $i++;
        }
        
        return $episodes;
    }

    class Episode {
        public $episode_number;
        public $title;
        public $description;
        public $pub_date;
        public $link;
        public $guid;
        
        public static function from_rss_item($item, $episode_number) {
            $episode = new self();
            $episode->episode_number = $episode_number;
            $episode->title = (string)$item->title;
            $episode->description = (string)$item->description;
            $episode->pub_date = (string)$item->pubDate;
            $episode->link = (string)$item->link;
            $episode->guid = (string)$item->guid;
            return $episode;
        }
    }

    function formatDate($dateString) {
        return date('j.n.Y', strtotime($dateString));
    }

    function getTruncatedDescription($description, $limit = 150) {
        // Strip HTML tags to get plain text
        $textContent = strip_tags($description);
        
        return strlen($textContent) > $limit 
            ? substr($textContent, 0, $limit) . '...'
            : $textContent;
    }

    // Fetch episodes
    $episodes = get_episodes($rss_url);
    $loading = false;
    ?>

    <script>
        function toggleDescription(guid) {
            const description = document.getElementById('desc-' + guid);
            const button = document.getElementById('btn-' + guid);
            const isExpanded = description.getAttribute('data-expanded') === 'true';
            
            if (isExpanded) {
                // Show truncated version
                description.textContent = description.getAttribute('data-truncated');
                description.setAttribute('data-expanded', 'false');
                button.textContent = 'Näytä loput';
            } else {
                // Show full version (as HTML)
                description.innerHTML = description.getAttribute('data-full');
                description.setAttribute('data-expanded', 'true');
                button.textContent = 'Näytä vähemmän';
            }
        }
    </script>

    <div>
        <header>
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <img src="logo.jpg" alt="Logo" />
                    </div>
                    <p class="slogan">Rento pelipodcast, jossa Kuomat juttelevat peleistä, joita he tykkäävät pelata</p>
                </div>
            </div>
        </header>

        <section class="hosts-section">
            <div class="container">
                <h2 class="section-title">Kuomat</h2>
                <div class="hosts-container">
                    <div class="host-card">
                        <img src="harri.jpeg" alt="Host 1" class="host-image" />
                        <h3 class="host-name">Harri Lappalainen</h3>
                        <p class="host-bio">Harri on lupsakka savolainen, joka nautiskelee erityisesti vuoropohjaisista roolipeleistä, taistelupeleistä ja roguelikeistä. Kaikenlainen retro toimii myös, ja Harrilta löytyykin useampi vanhempi pelikonsoli joiden antia hän saattaa välillä esitellä. Hän pelaa Nintendo Switchillä, PS5:lla ja nuhapumppu PC:llä.</p>
                    </div>
                    <div class="host-card">
                        <img src="jere.jpeg" alt="Host 2" class="host-image" />
                        <h3 class="host-name">Jere Karppinen</h3>
                        <p class="host-bio">Jere on vitsiä murjova pohjoiskarjalainen, joka aloitti seikkailupelien koluamisen jo hyvin nuorella iällä ja on edelleen sillä tiellä. Hän on valmis kuitenkin kokeilemaan monenlaisia pelejä ja saattaa joskus yllättää sillä, mitä jaksoon tuo! Jere käyttää pelaamiseen PC:tä, PS5 ja Steam Deckiä.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- <div class="search-container">
            <div class="container">
                <form class="search-form" action="#" method="get">
                    <input 
                        type="text" 
                        class="search-input" 
                        placeholder="Hae jaksoja hakusanalla" 
                        aria-label="Search podcasts"
                        name="q"
                    />
                </form>
            </div>
        </div> -->

        <section class="episodes-section">
            <div class="container">
                <h2 class="section-title">Jaksot</h2>
                <?php if ($loading): ?>
                    <div class='loading-container'>
                        <div class="spinner"></div>
                    </div>
                <?php else: ?>
                    <div class="episodes-container">
                        <?php foreach ($episodes as $episode): ?>
                            <?php 
                                $truncatedDesc = getTruncatedDescription($episode->description);
                                $needsToggle = strlen(strip_tags($episode->description)) > 150;
                            ?>
                            <div class="episode-card">
                                <div class="episode-info">
                                    <span class="episode-number"><?php echo htmlspecialchars($episode->episode_number); ?>.</span>
                                    <span class="episode-date"><?php echo formatDate($episode->pub_date); ?></span>
                                </div>
                                <h3 class="episode-title"><?php echo htmlspecialchars($episode->title); ?></h3>
                                <div 
                                    class="episode-description" 
                                    id="desc-<?php echo htmlspecialchars($episode->guid); ?>"
                                    data-expanded="false"
                                    data-truncated="<?php echo htmlspecialchars($truncatedDesc); ?>"
                                    data-full="<?php echo htmlspecialchars($episode->description); ?>"
                                >
                                    <?php echo htmlspecialchars($truncatedDesc); ?>
                                </div>
                                <div class="episode-actions">
                                    <a 
                                        href="<?php echo htmlspecialchars($episode->link); ?>" 
                                        class="episode-button" 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                    >
                                        Kuuntele Spotifyssä
                                    </a>
                                    <?php if ($needsToggle): ?>
                                        <button 
                                            class="episode-button" 
                                            id="btn-<?php echo htmlspecialchars($episode->guid); ?>"
                                            onclick="toggleDescription('<?php echo htmlspecialchars($episode->guid); ?>')"
                                        >
                                            Näytä loput
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="social-links">
                    kuomatpelaa at gmail dot com
                </div>
                <p>&copy; 2025 Kuomat Pelaa Podcast. All Rights Reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>