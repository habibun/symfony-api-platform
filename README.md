## About The Project  
[Symfony][symfony_website] with [API Platform][api_platform_website]
 
Please check the [projects](#projects) section for more details.

## Overview
| Branch                       | API Platform Version | Symfony Version | PHP Version |
|------------------------------|----------------------|-----------------|-------------|
| [v2-extending][v2-extending] | `^2.7`               | `^5.4`          | `^8.0`      |
| [v2-security][v2-security]   | `^2.7`               | `^5.4`          | `^8.0`      |
| [v2][v2]                     | `^2.7`               | `^5.4`          | `^8.0`      |


## Projects

<details><summary> Sample application using API Platform V2 Custom Resources </summary>  
<p>  

<img
src="https://user-images.githubusercontent.com/5810350/230716121-d2dd0ce9-4942-4a14-a8db-6551d2d9b10d.png"
alt="Api platform 2 extending frontend"
width="50%"
/>

<img
src="https://user-images.githubusercontent.com/5810350/230716134-0b505341-a0e4-4662-9783-b7e869871357.png"
alt="API Platform2 extending backend"
width="50%"
/>

**Code:** https://github.com/habibun/symfony-api-platform/tree/v2-extending  
**Resources:**
- [API Platform Part 3: Custom Resources](https://symfonycasts.com/screencast/api-platform-extending)
<br/>

### Prerequisites
- [Symfony CLI][symfony_cli], [PHP][php], [Composer][composer], [Git][git], [Node.js][node]


### Installation

```bash 
# Clone the repository using the command
git clone git@github.com:habibun/symfony-api-platform.git

# Navigate into the cloned directory
cd symfony-api-platform

# Checkout to project branch
git checkout v2-extending

# Create .env.local file
make init
```

Configure the database connection in the .env.local file

```bash 
# Install project
make install

# Start the local development server
make start
```

Please check more rules in [Makefile][v2_extending_makefile].

</p>

##
</details>

<details><summary>Sample application using API Platform V2 Security</summary>  
<p>  

<img
src="https://user-images.githubusercontent.com/5810350/226957115-5f6f896a-6cd6-45b0-9d07-1447e1d4d614.png"
alt="Symfony API Platform Project With Security"
width="50%"
/>

**Code:** https://github.com/habibun/symfony-api-platform/tree/v2-security  
**Resources:**
- [API Platform Part 2: Security](https://symfonycasts.com/screencast/api-platform2-security)
<br/>


#### Installation
```bash
git clone git@github.com:habibun/symfony-api-platform.git
cd symfony-api-platform
git checkout v2-security
symfony composer install
```

</p>
</details>

<details><summary>Sample application using API Platform V2</summary>
<p>  

<img
src="https://user-images.githubusercontent.com/5810350/226957115-5f6f896a-6cd6-45b0-9d07-1447e1d4d614.png"
alt="Symfony API Platform Project With Security"
width="50%"
/>

**Code:** https://github.com/habibun/symfony-api-platform/tree/v2-security  
**Resources:**
- [API Platform Part 2: Security](https://symfonycasts.com/screencast/api-platform2-security)
<br/>


#### Installation
```bash
git clone git@github.com:habibun/symfony-api-platform.git
cd symfony-api-platform
git checkout v2
symfony composer install
```

</p>
</details>


## Learn More
- [API Platform Docs][api_platform_docs]
- [Swagger Docs][swagger_docs]
- [Swagger UI](https://swagger.io/tools/swagger-ui/)
- [RDF](https://www.w3.org/RDF/)
- [JSON-LD](https://en.wikipedia.org/wiki/JSON-LD)
- [OpenAPI Specification](https://oai.github.io/Documentation/)
- [Hydra](https://www.hydra-cg.com/)
- [A simple PHP API extension for DateTime](https://github.com/briannesbitt/carbon)
- [The Serializer Component](https://symfony.com/doc/5.4/components/serializer.html)

## Related
- [Symfony](https://github.com/habibun/symfony)  


## License
Distributed under the MIT License. See **[LICENSE][license]** for more information.



[//]: # (Links)
[license]: https://github.com/habibun/symfony-api-platform/blob/main/LICENSE
[symfony_website]: https://symfony.com/

[api_platform_website]: https://api-platform.com/
[api_platform_docs]: https://api-platform.com/docs
[swagger_docs]: https://swagger.io/docs/

[v2]: https://github.com/habibun/symfony-api-platform/tree/v2
[v2_tt]: https://github.com/habibun/symfony-api-platform/tree/v2 "Sample application using API Platform V2"

[v2-security]: https://github.com/habibun/symfony-api-platform/tree/v2-security
[v2-security_tt]: https://github.com/habibun/symfony-api-platform/tree/v2-security "Sample application using API Platform V2 Securiity"

[v2-extending]: https://github.com/habibun/symfony-api-platform/tree/v2-extending
[v2-extending_tt]: https://github.com/habibun/symfony-api-platform/tree/v2-extending "Sample application using API Platform V2 Custom Resources"
[v2_extending_makefile]: https://github.com/habibun/symfony-api-platform/blob/v2-extending/Makefile


[symfony_cli]: https://symfony.com/download
[php]: https://www.php.net/
[composer]: https://getcomposer.org/
[git]: https://git-scm.com/
[node]: https://nodejs.org/
