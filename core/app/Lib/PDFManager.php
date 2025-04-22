<?php

namespace App\Lib;

use PDF;

/**
 * A PHP class to provide the basic functionality to download data as pdf file.
 */
class PDFManager
{
    /**
     * PCManager PDF and CSV manager.
     */

    /**
     * @var string $pageTitle contains the title of the pdf file
     */
    protected $pageTitle;

    /**
     * @var string $viewName contains the name of the blade file
     */
    protected $viewName;

    /**
     * @var array $data contains all variables to be passed in the blade file
     */
    protected $data;


    /**
     * Class constructor
     * This will start a new pdf file generation
     *
     * @param string $viewName the view file where the pdf view will be rendered
     * @param string $pageTitle the title to be displayed in heading
     * @param array $data the variables to be passed in the view/blade file
     */

    public function __construct($viewName, $data)
    {
        $this->viewName = $viewName;
        $this->data = $data;
        $this->pageTitle = $data['pageTitle'];
    }

    /**
     * Generate the pdf file and return the file to download
     *
     * @return Illuminate\Http\Response
     */

    public function generatePDF()
    {
        $pdf = PDF::loadView($this->viewName, $this->data);
        return $pdf->download(slug($this->pageTitle) . '-' . time() . '.pdf');
    }
}
