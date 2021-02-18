<?php


namespace Plugin\DataMigration4\Tests\Web\Admin;


use Eccube\Common\Constant;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ConfigControllerTest extends AbstractAdminWebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testファイルアップロードテスト()
    {
        $project_dir = self::$container->getParameter('kernel.project_dir');

        $file = $project_dir.'/app/Plugin/DataMigration4/Tests/Fixtures/foo.zip';
        $testFile = $project_dir.'/app/Plugin/DataMigration4/Tests/Fixtures/test.zip';

        $fs = new Filesystem();
        $fs->copy($file, $testFile);

        $file = new UploadedFile($testFile, 'test.zip', 'application/zip', null, null, true);
        $this->client->request(
            'POST',
            $this->generateUrl('data_migration4_admin_config'),
            [
                'config' => [
                    Constant::TOKEN_NAME => 'dummy',
                    'import_file' => $file
                ]
            ],
            [
                'import_file' => $file
            ]
        );

        self::assertTrue($this->client->getResponse()->isRedirect($this->generateUrl('data_migration4_admin_config')));
    }
}
