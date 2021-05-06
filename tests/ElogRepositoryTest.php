<?php


use Jlab\ElogRepository\ElogRepository;
/**
 * Class ElogRepositoryTest
 *
 * Note that the tests in this file presume (read-only) access to
 * existing data in the current jefferson lab logbook at
 * https://logbooks.jlab.org.
 */
class ElogRepositoryTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @var ElogRepository
     */
    protected $repo;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new ElogRepository();
    }

    /**
     * A logentry known to exist as of 5/6/21
     */
    public function test_it_verifies_a_known_logentry_exists(){
        $this->assertTrue($this->repo->exists(3875817));
    }

    /**
     * A logentry known to exist as of 5/6/21
     */
    public function test_it_returns_null_for_not_exists(){
        $this->assertFalse($this->repo->exists(105));
    }


    /**
     * A logentry known to exist as of 5/6/21
     */
    public function test_it_finds_a_known_logentry(){
        $entry = $this->repo->find(3875817);
        $this->assertNotEmpty($entry);
        $this->assertEquals('iocnl3 restored', $entry['title']);
    }

    public function test_it_finds_entries_using_where_clauses(){
        $entries = $this->repo->where('title','iocnl3 restored')
            ->where('startdate','2021-05-06')
            ->where('enddate', '2021-05-07')
            ->where('book','SLOG')
            ->get();
        $this->assertContains('iocnl3 restored', $entries->pluck('title'));
    }

    public function test_it_finds_multiple_matching_entries_using_where_clauses(){
        $entries = $this->repo->where('title','restored')
            ->where('startdate','2021-05-06')
            ->where('enddate', '2021-05-07')
            ->where('book','SLOG')
            ->get();
        $this->assertGreaterThan(6, $entries->count());
    }

    public function test_it_returns_empty_collection_for_no_match(){
        $entries = $this->repo->where('title','hamster huey')
            ->where('startdate','2021-05-06')
            ->where('enddate', '2021-05-07')
            ->where('book','SLOG')
            ->get();
        $this->assertEmpty($entries);
    }

    /**
     * Verify that we can override the default URL.
     */
    function test_it_uses_custom_url(){
        $this->repo = new ElogRepository('https://logbooks.jlab.org/api/elog');
        $this->test_it_verifies_a_known_logentry_exists();
    }

    /**
     * @inheritDoc
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return
            [
                \Jlab\ElogRepository\ElogRepositoryServiceProvider::class,
            ];
    }
}
