<?php

namespace App\Controller;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageController extends AbstractController
{
    #[Route('/image', name: 'image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig', [
            'controller_name' => 'ImageController',
        ]);
    }

    public function newNameImage(SluggerInterface $slugger, $imageFile)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);        
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();  
        try {
            $imageFile->move(
                $this->getParameter('img_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }
        return $newFilename;
    }
    public function uploadNewFileName(SluggerInterface $slugger, $imageFile,$nameDirectiry)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME); 
        //dd($originalFilename);      
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension(); 
        //dd($newFilename);       
        try {
            $imageFile->move(
                $this->getParameter($nameDirectiry),
                $newFilename
            );
        } catch (FileException $e) {
            echo "An error occurred while creating your directory at ";
        }           
        return $newFilename;   
    }
    public function deleteImageFile($nameObject,$getImageFile,$setImageFile,$nameDirectiry){
        $filesystem = new Filesystem();   
        if($nameObject->$getImageFile() != null){
            $nameObject->$setImageFile(
             $path1 = new File($this->getParameter($nameDirectiry).'/'.$nameObject->$getImageFile())
            ); 
            $filesystem->remove(['symlink', $path1, $nameObject->$getImageFile()]);
        }      
    }
}
