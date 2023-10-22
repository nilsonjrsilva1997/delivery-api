<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service providader';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // criando controller com comando
        $service = $this->argument('service');

        $campos = explode('-', $service);

        $service = $campos[0];

        // 'Bank' => App\Bank::class,
        // 'BankValidateService' => App\Http\Services\BankValidateService::class,

        // $this->info("File {$path} created.");
        // $filename = 'config/app.php'; // the file to change
        // $search = "aliases' => ["; // the content after which you want to insert new stuff
        // $insert = "        '".$service."' => App\\".$service."::class,
        // '".$service."ValidateService' => App\Http\Services\\".$service."ValidateService::class,"; // your new stuff

        // $replace = $search. "\n". $insert;

        // file_put_contents($filename, str_replace($search, $replace, file_get_contents($filename)));


        $path = $this->viewPathController(lcfirst($service));

        $this->createDir($path);

        if (File::exists($path))
        {
            $this->error("File {$path} already exists!");
            return;
        }

        $campos_rules = '';
        $campos_rules2 = '';
        // pegando campos
        for($i=0; $i < count($campos); $i++) {
            $campo = [];

            if($i != 0) {
                $campo = (explode('.', $campos[$i]));

                if($campo[0] == 'float') {
                    $campos_rules .= "        '".$campo[1]."' => 'required|numeric',
    ";
                }

                if($campo[0] == 'string') {
                    $campos_rules .= "        '".$campo[1]."' => 'required|string|max:255',
    ";
                }

                if($campo[0] == 'integer') {
                    $campos_rules .= "        '".$campo[1]."' => 'required|integer',
    ";
                }

                if($campo[0] == 'boolean') {
                    $campos_rules .= "        '".$campo[1]."' => 'required|boolean',
    ";
                }

                if($campo[0] == 'date') {
                    $campos_rules .= "        '".$campo[1]."' => 'required|date',
    ";
                }
            }

            if($i != 0) {
                $campo = (explode('.', $campos[$i]));

                if($campo[0] == 'float') {
                    $campos_rules2 .= "        '".$campo[1]."' => 'numeric',
    ";
                }

                if($campo[0] == 'string') {
                    $campos_rules2 .= "        '".$campo[1]."' => 'string|max:255',
    ";
                }

                if($campo[0] == 'integer') {
                    $campos_rules2 .= "        '".$campo[1]."' => 'integer',
    ";
                }

                if($campo[0] == 'boolean') {
                    $campos_rules2 .= "        '".$campo[1]."' => 'boolean',
    ";
                }

                if($campo[0] == 'date') {
                    $campos_rules2 .= "        '".$campo[1]."' => 'date',
    ";
                }
            }





        }
        

        File::put($path,
            "<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\\".$service.";

class ".$service."Controller extends Controller
{
    public function index()
    {
        return ".ucfirst($service)."::all();
    }

    public function show($"."id)
    {
        $".lcfirst($service)." = ".$service."::findOrFail($"."id);
        return $".lcfirst($service).";
    }

    public function create(Request $"."request)
    {
        $"."validatedData = $"."request->validate([
            ".$campos_rules."    ]);

        return ".$service."::create($"."validatedData);
    }

    public function update(Request $"."request, $"."id)
    {
        $"."validatedData = $"."request->validate([
            ".$campos_rules2."    ]);

        $".lcfirst($service)." = ".$service."::findOrFail($"."id);
        $".lcfirst($service)."->fill($"."validatedData);
        $".lcfirst($service)."->save();
        return $".lcfirst($service).";
    }

    public function destroy($"."id)
    {
        $".lcfirst($service)." = ".$service."::findOrFail($"."id);
        $".lcfirst($service)."->delete();
    }
}
");

// criando model
//$service = $this->argument('service');
        $path = $this->viewPathModel($service);

        $ultimaLetra = substr($service, -1);

     


        $this->createDir($path);

        if (File::exists($path))
        {
            $this->error("File {$path} already exists!");
            return;
        }


        $campos_string_model = '';

// pegando campos
        for($i=0; $i < count($campos); $i++) {
            $campo = [];
            if($i != 0) {
                $campo = (explode('.', $campos[$i]));
                $campos_string_model .= "'".$campo[1]."'" . ', ';
            }
        }
        $name = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $service )), '_');
        $final = '';

        if($ultimaLetra == 'y') {
            $name = substr(strtolower($name), 0, -1);
            $final = 'ies';
        } else {
            $final = 's';
        }

        File::put($path,
            "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ".$service." extends Model
{
    use HasFactory;
    protected $"."table = '".strtolower($name).$final."';
    protected $"."fillable = [".$campos_string_model."];
}
");


// coloca dados no api
// $service = $this->argument('service');

        $path = $this->viewPathRoutes($service);
        $name = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $service )), '_');
        File::append($path, "
        Route::group(['prefix' => '".strtolower($name)."'], function () {
            Route::get('/', [".$service."Controller::class, 'index']);
            Route::get('{id}/', [".$service."Controller::class, 'show']);
            Route::post('/', [".$service."Controller::class, 'create']);
            Route::put('{id}', [".$service."Controller::class, 'update']);
            Route::delete('{id}', [".$service."Controller::class, 'destroy']);
        });
        ");


// criando model
// $service = $this->argument('service');

        $path = $this->viewPathMigrations($service);

        $this->createDir($path);

        if (File::exists($path))
        {
            $this->error("File {$path} already exists!");
            return;
        }

        $ultimaLetra = substr($service, -1);

      


        $campos_string = '';
        // pegando campos
        for($i=0; $i < count($campos); $i++) {
            $campo = [];
            if($i != 0) {
                $campo = (explode('.', $campos[$i]));
                $campos_string .= "$"."table->".$campo[0]."('".$campo[1]."');" . "
            ";
            }
        }


        $name = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $service )), '_');

        $final = '';

        if($ultimaLetra == 'y') {            
            $name = substr(strtolower($name), 0, -1);
            $final = 'ies';
        } else {
            $final = 's';
        }
        
        File::put($path,
            "<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class Create".$service."sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('".strtolower($name).$final."', function (Blueprint $"."table) {
            $"."table->id();
            ".$campos_string."
            $"."table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('".strtolower($name).$final."');
    }
}
");

        // $path = $this->viewPath($service);

        // $this->createDir($path);

        // if (File::exists($path))
        // {
        //     $this->error("File {$path} already exists!");
        //     return;
        // }




//         File::put($path,
//             '<?php
// namespace App\Http\Services;
// class ' .$service .'ValidateService
// {
//     public function getValidateRulesCreate()
//     {
//         $validateAttributes =
//         [
//             '.$campos_rules.'
//         ];
//         return $validateAttributes;
//     }
//     public function getValidateRulesUpdate()
//     {
//         $validateAttributes =
//         [
//             '.$campos_rules2.'
//         ];
//         return $validateAttributes;
//     }
// }
// ');




    }

    /**
     * Get the service full path.
     *
     * @param string $service
     *
     * @return string
     */
    public function viewPath($service)
    {
        $service = str_replace('.', '/', $service) . 'ValidateService.php';

        $path = "app/Http/Services/{$service}";

        return $path;
    }

    public function viewPathController($service)
    {
        $service = str_replace('.', '/', ucfirst($service)) . 'Controller.php';

        $path = "app/Http/Controllers/{$service}";

        return $path;
    }

    public function viewPathModel($service)
    {
        $service = str_replace('.', '/', $service);

        $path = "app/Models/{$service}" . ".php";

        return $path;
    }


    public function viewPathRoutes($service)
    {
        $service = str_replace('.', '/', $service);

        $path = "routes/api.php";

        return $path;
    }


    public function viewPathMigrations($service)
    {
        $service = str_replace('.', '/', $service);
        $name = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $service )), '_');
        $ultimaLetra = substr($service, -1);

        $final = '';

        if($ultimaLetra == 'y') {
            
            $name = substr(strtolower($name), 0, -1);
            $final = 'ies';
        } else {
            $final = 's';
        }
        $agora = date('Y_m_d');
        $path = "database/migrations/" . $agora . "_".date('H'). date('m') . date('s') . "_create_" . strtolower($name) . $final ."_table.php";

        return $path;
    }

    /**
     * Create service directory if not exists.
     *
     * @param $path
     */
    public function createDir($path)
    {
        $dir = dirname($path);

        if (!file_exists($dir))
        {
            mkdir($dir, 0777, true);
        }
    }
}