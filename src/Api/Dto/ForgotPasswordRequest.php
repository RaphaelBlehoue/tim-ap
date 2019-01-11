<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 30/12/2018
 * Time: 13:33
 */

namespace App\Api\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "method"="POST",
 *              "path"="/users/forgot-password-request",
 *              "validation_groups"={"request_mail_valid"},
 *              "denormalization_context"={"groups"={"request.mail"}},
 *              "swagger_context" = {
 *                 "responses" = {
 *                      "201" = {
 *                          "description" = "email with reset token has been sent",
 *                          "schema" =  {
 *                              "type" = "object",
 *                              "required" = {
 *                                  "email"
 *                              },
 *                              "properties" = {
 *                                   "success" = {
 *                                      "type" = "boolean"
 *                                   }
 *                              }
 *                          }
 *                      },
 *                  }
 *              }
 *          },
 *      },
 *      itemOperations={
 *          "put"={
 *              "method"="PUT",
 *              "path"="/users/update-password-request/{id}",
 *              "validation_groups"={"request_password_valid"},
 *              "denormalization_context"={"groups"={"request.password"}}
 *          },
 *     },
 * )
 */
final class ForgotPasswordRequest
{
    /**
     * @Assert\NotBlank(message="Vous avez renseignez une valeur vide", groups={"request_mail_valid"})
     * @Assert\Email(message="Le format de l'email est invalide")
     * @ApiProperty(attributes={
     *     "swagger_context"={
     *         "type":"string",
     *         "example"="example@domain.com"
     *     }
     * })
     * @Groups({"request.mail"})
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Vous avez renseignez une valeur vide", groups={"request_password_valid"})
     * @ApiProperty(attributes={
     *     "swagger_context"={
     *         "type":"string",
     *     }
     * })
     * @Groups({"request.password"})
     */
    public $password;

    /**
     * @Assert\NotBlank(message="Vous avez renseignez une valeur vide", groups={"request_password_valid"})
     * @ApiProperty(attributes={
     *     "swagger_context"={
     *         "type":"string",
     *     }
     * })
     * @Groups({"request.password"})
     */
    public $plainPassword;
}