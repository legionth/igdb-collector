<?php

namespace Legionth\React\IGDBCollector;

use Legionth\React\IGDB\IgdbClient;
use React\MySQL\ConnectionInterface;
use React\MySQL\Factory;
use React\MySQL\QueryResult;
use React\Promise\Promise;

class GameCollector
{
    /**
     * @var IgdbClient
     */
    private $igdbClient;

    /**
     * @var Factory
     */
    private $mysqlFactor;

    /**
     * @param IgdbClient $igdbClient
     * @param Factory $factory
     * @param string $databaseUri
     */
    public function __construct(
        IgdbClient $igdbClient,
        Factory $factory,
        string $databaseUri
    ) {
        $this->igdbClient = $igdbClient;
        $this->mysqlFactor = $factory;
        $this->databaseUri = $databaseUri;
    }

    /**
     * @param int $startingId
     * @param int $limit
     */
    public function collectGames(int $startingId, int $limit)
    {
        $promise = new Promise(function ($resolve, $reject) use ($startingId, $limit) {
            $ids = array();
            for ($i = 0; $i < $limit; $i++) {
                $ids[] =  $startingId + $i;
            }

            $igdbPromise = $this->igdbClient->getGames($ids, array('limit' => 50));

            $resolve($igdbPromise);
        });

        $factory = $this->mysqlFactor;

        $databaseUri = $this->databaseUri;
        $connectionPromise = $factory->createConnection($databaseUri);

        $promise->then(function ($games) use ($factory, $databaseUri, $connectionPromise) {
           $connectionPromise->then(function (ConnectionInterface $connection) use ($games) {
               echo 'Connected' . PHP_EOL;
               foreach ($games as $key => $game) {
                   echo 'Game: ' . json_encode($game) . PHP_EOL;

                   $sql = 'INSERT INTO igdb_games(
        `id`,
        `name`,
        `slug`,
        `url`,
        `created_at`,
        `updated_at`,
        `summary`,
        `storyline`,
        `collection`,
        `franchise`,
        `hypes`,
        `popularity`,
        `rating`,
        `rating_count`,
        `aggregated_rating_count`,
        `total_rating`,
        `total_rating_count`,
        `game`,
        `version_parent`,
        `developers`,
        `publishers`,
        `game_engines`,
        `category`,
        `time_to_beat`,
        `player_perspectives`,
        `game_modes`,
        `keywords`,
        `themes`,
        `genres`,
        `platforms`,
        `first_release_date`,
        `status`,
        `release_dates`,
        `alternative_names`,
        `screenshots`,
        `videos`,
        `cover`,
        `esrb`,
        `pegi`,
        `websites`,
        `tags`,
        `dlcs`,
        `expansions`,
        `standalone_expansions`,
        `bundles`,
        `games`,
        `external`,
        `artworks`
    ) VALUES
    (
        ' . $game['id'] . ',
        "' . $game['name'] . '",
        "' . $game['slug'] . '",
        "' . $game['url'] . '",
        ' . $game['created_at']. ',
        ' . $game['updated_at'] .',
       ' . $this->extractVakue('summary', $game, 'string') . ',
       ' . $this->extractVakue('storyline', $game, 'string') . ',
       ' . $this->extractVakue('collection', $game, 'string') . ',
       ' . $this->extractVakue('franchise', $game, 'string') . ',
       ' . $this->extractVakue('hypes', $game, 'string') . ',
       ' . $this->extractVakue('popularity', $game, 'string') . ',
       ' . $this->extractVakue('rating', $game, 'string') . ',
       ' . $this->extractVakue('rating_count', $game, 'string') . ',
       ' . $this->extractVakue('aggregated_rating_count', $game, 'string') . ',
       ' . $this->extractVakue('total_rating', $game, 'string') . ',
       ' . $this->extractVakue('total_rating_count', $game, 'string') . ',
       ' . $this->extractVakue('game', $game, 'string') . ',
       ' . $this->extractVakue('version_parent', $game, 'string') . ',
       ' . $this->extractVakue('developers', $game, 'string') . ',
       ' . $this->extractVakue('publishers', $game, 'string') . ',
       ' . $this->extractVakue('game_engines', $game, 'string') . ',
       ' . $this->extractVakue('category', $game, 'string') . ',
       ' . $this->extractVakue('time_to_beat', $game, 'string') . ',
       ' . $this->extractVakue('player_perspectives', $game, 'string') . ',
       ' . $this->extractVakue('game_modes', $game, 'string') . ',
       ' . $this->extractVakue('keywords', $game, 'string') . ',
       ' . $this->extractVakue('themes', $game, 'string') . ',
       ' . $this->extractVakue('genres', $game, 'string') . ',
       ' . $this->extractVakue('platforms', $game, 'string') . ',
       ' . $this->extractVakue('first_release_date', $game, 'string') . ',
       ' . $this->extractVakue('status', $game, 'string') . ',
       ' . $this->extractVakue('release_dates', $game, 'string') . ',
       ' . $this->extractVakue('alternative_names', $game, 'string') . ',
       ' . $this->extractVakue('screenshots', $game, 'string') . ',
       ' . $this->extractVakue('videos', $game, 'string') . ',
       ' . $this->extractVakue('cover', $game, 'string') . ',
       ' . $this->extractVakue('esrb', $game, 'string') . ',
       ' . $this->extractVakue('pegi', $game, 'string') . ',
       ' . $this->extractVakue('websites', $game, 'string') . ',
       ' . $this->extractVakue('tags', $game, 'string') . ',
       ' . $this->extractVakue('dlcs', $game, 'string') . ',
       ' . $this->extractVakue('expansions', $game, 'string') . ',
       ' . $this->extractVakue('standalone_expansions', $game, 'string') . ',
       ' . $this->extractVakue('bundles', $game, 'string') . ',
       ' . $this->extractVakue('games', $game, 'string') . ',
       ' . $this->extractVakue('external', $game, 'string') . ',
       ' . $this->extractVakue('artworks', $game, 'string') .')';

                   echo  PHP_EOL . PHP_EOL;
                   echo $sql;
                   echo  PHP_EOL . PHP_EOL;

                   $connection->query($sql)->then(
                       function (QueryResult $command) {
                           echo "wut";
                           if ($command->insertId !== 0) {
                               // this is a response to a SELECT etc. with some rows (0+)
                               print_r($command->resultFields);
                               print_r($command->resultRows);
                               echo count($command->resultRows) . ' row(s) in set' . PHP_EOL;
                           }
                       },
                       function (Exception $error) {
                           echo 'Error: ' . $error->getMessage() . PHP_EOL;
                       }
                   );
               }
            }, 'printf');
        });

    }

    private function extractVakue(string $key, array $array, string $type)
    {
        $result = "NULL";

        if (isset($array[$key])) {
            $replacements = array(
                "\x00" => '\x00',
                "\n" => '\n',
                "\r" => '\r',
                "\\" => '\\\\',
                "'" => "\'",
                '"' => '\"',
                "\x1a" => '\x1a'
            );

            if (true === is_array($array[$key])) {
                $array[$key] = json_encode($array[$key]);
            }
            $result = strtr($array[$key],$replacements);

            if ($type === 'string') {
                $result = '"' .  $result . '"';
            }
        }

        return $result;
    }
}