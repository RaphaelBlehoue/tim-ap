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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "method"="POST",
 *              "path"="/users/code-password-reset",
 *              "swagger_context" = {
 *                 "responses" = {
 *                      "201" = {
 *                          "description" = "Code to request password verify",
 *                          "schema" = {
 *                              "type" = "array",
 *                              "items" = { "$ref" = "#/definitions/User-user.read" }
 *                          }
 *                      },
 *                      "400" = {
 *                          "description" = "Invalid input"
 *                      },
 *                      "404" = {
 *                          "description" = "resource not found"
 *                      }
 *                  }
 *              }
 *          },
 *      },
 *      itemOperations={},
 * )
 */
final class GetUserPasswordRequest
{
    /**
     * @Assert\NotBlank(message="Vous avez renseignez une valeur vide")
     * @Assert\Length(
     *      min="6",
     *      max = 6,
     *      maxMessage = "Your code length cannot be longer than {{ limit }} characters",
     *      minMessage = "Your code length cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="Le type du format de code est invalide"
     * )
     * @ApiProperty(attributes={
     *     "swagger_context"={
     *         "type":"integer",
     *         "example"= 123445
     *     }
     * })
     */
    public $code;
}