<?php


namespace AppBundle\Controller\API;
use AppBundle\Entity\Media;
use AppBundle\File\FileUploader;
use AppBundle\Type\MediaType;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Class MediaController
 * @package AppBundle\Controller\API
 * @Route(name="api_media_")
 */
class MediaController extends AbstractAPIController
{

    /**
     * @Route("/media", name="upload")
     * @Method({"POST"})
     * @Doc\Tag(name="medias")
     * @Doc\Parameter(
     *      name="media",
     *      in="body",
     *      type="json",
     *      description="data for the media to be uploaded",
     *      required=true,
     *      @Doc\Schema(
     *          @Model(type=Media::class)
     *      )
     * )
     * @Doc\Response(
     *     response=201,
     *     description="The media has been successfully uploaded",
     * )
     * @Doc\Response(
     *     response=400,
     *     description="The media couldn't have been uploaded, due to following validation errors.",
     * )
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param RouterInterface $router
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadAction(Request $request, FileUploader $fileUploader, SerializerInterface $serializer, ValidatorInterface $validator, RouterInterface $router)
    {
        $media = new Media();

        $media->setFile($request->files->get('file'));

        $constraintViolationList = $validator->validate($media);

        if ($constraintViolationList->count() === 0) {
            $em = $this->getDoctrine()->getManager();

            $generatedFileName = $fileUploader->upload($media->getFile(), time());

            //FIXME: Find a better way to catch request base url
            $baseUrl = $router->getContext()->getScheme().'://'.$router->getContext()->getHost();
            $filePath = $baseUrl.$this->getParameter('upload_dir').'/'.$generatedFileName;

            $media->setFilePath($filePath);

            $em->persist($media);
            $em->flush();

            dump($media); die;

            return $this->respondWithJson(
                'Media uploaded',
                Response::HTTP_CREATED
            );
        }

        return $this->respondWithJson(
            $serializer->serialize(
                $constraintViolationList,
                'json'),
            Response::HTTP_BAD_REQUEST
        );
    }

}