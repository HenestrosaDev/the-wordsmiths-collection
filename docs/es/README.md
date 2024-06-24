<div id="top"></div>

<!-- PROJECT SHIELDS -->
<!--
*** I am using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->

<!-- PROJECT LOGO -->
<br />
<div align="center">
	<img 
		src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/public/favicon.svg" 
		alt="Logo" 
		width="156" 
		height="156"
	>
	<h1 align="center">The Wordsmith's Collection</h1>
	<p align="center">Un sitio web con un modelo SaaS que permite a los usuarios acceder a una biblioteca selecta de las obras literarias más importantes de la historia.</p>
	<p>
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/stargazers">
			<img 
				alt="GitHub Contributors" 
				src="https://img.shields.io/github/stars/HenestrosaDev/the-wordsmiths-collection" 
			>
		</a>
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/graphs/contributors">
			<img 
				alt="GitHub Contributors" 
				src="https://img.shields.io/github/contributors/HenestrosaDev/the-wordsmiths-collection" 
			>
		</a>
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/issues">
			<img 
				alt="Issues" 
				src="https://img.shields.io/github/issues/HenestrosaDev/the-wordsmiths-collection" 
			>
		</a>
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/pulls">
			<img 
				alt="GitHub pull requests" 
				src="https://img.shields.io/github/issues-pr/HenestrosaDev/the-wordsmiths-collection" 
			>
		</a>
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/LICENSE">
			<img 
				alt="GitHub pull requests" 
				src="https://img.shields.io/github/license/HenestrosaDev/the-wordsmiths-collection" 
			>
		</a>
	</p>
	<p>
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/issues/new/choose">
			Reportar Error
		</a> 
		· 
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/issues/new/choose">
			Solicitar Funcionalidad
		</a> 
		· 
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/discussions">
			Realizar Pregunta
		</a>
	</p>
	<p>
		<a href="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/README.md/">🇬🇧 English</a> | 🇪🇸 Español
	</p>
</div>

[![Presentation video](/docs/common/youtube-thumbnail.png)](https://youtu.be/nN5XyJb1UYE)
>Vídeo en el que se explican las principales características del sitio web.

<!-- TABLE OF CONTENTS -->

## Tabla de Contenidos

- [Sobre el Proyecto](#sobre-el-proyecto)
	- [Hecho Con](#hecho-con)
	- [Modelo Entidad-Relación](#modelo-entidad-relación)
	- [Modelo Relacional](#modelo-relacional)
	- [Casos de Uso](#casos-de-uso)
- [Primeros Pasos](#primeros-pasos)
	- [Requisitos previos](#requisitos-previos)
	- [Pasos](#pasos)
- [Notas](#notas)
- [Hoja de Ruta](#hoja-de-ruta)
- [Autores](#autores)
- [Licencia](#licencia)

<!-- ABOUT THE PROJECT -->

## Sobre el Proyecto

**The Wordsmith's Collection** es un sitio web basado en suscripciones (SaaS) que permite a los usuarios acceder a una biblioteca de las obras literarias más importantes de la historia. Los usuarios suscritos podrán leer y reseñar los libros disponibles para su plan de suscripción. Cada libro, género y autor tiene descripciones detalladas para proporcionar información sobre su sinopsis, temas y bibliografía, respectivamente.

Para acceder al servicio, los usuarios deben registrarse e introducir una tarjeta de crédito para el pago. Ten en cuenta que **no se realiza ningún pago**, ya que el sitio sólo verifica que la tarjeta de crédito es válida. Para utilizar realmente esta función, se debe implementar un proveedor de pasarela de pago como Redsys o Stripe.

Hay dos planes de suscripción:

- **Basic**: Puede leer y revisar todos los libros excepto los marcados como **Premium**.
- **Premium**: Puede leer y revisar toda la biblioteca, incluidos los libros **Premium**.

El sitio web tiene tres tipos de usuarios:

- **Visitante no suscrito**: No puede leer libros ni escribir reseñas, pero puede ver la biblioteca disponible y las reseñas publicadas.
- **Suscriptor**: Usuario registrado que paga una cuota mensual/anual para leer libros. Puede ser **Básico** o **Premium**.
- **Administrador**: Usuario que tiene todas las características de un suscriptor, además de la capacidad de añadir, editar y eliminar libros, géneros, y autores. También puede editar y eliminar usuarios, y borrar reseñas.

<details>
	<summary>Capturas de pantalla</summary>

	<p align="center">
		<img 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/landing.png" 
			alt="Landing"
			title="Landing"
		>
	</p> 
	
	<p align="center">
		<img 
			width="49%" 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/genre-detail.png" 
			alt="Página de detalle de género"
			title="Página de detalle de género"
		>
		&nbsp;
		<img 
			width="49%" 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/book-detail.png" 
			alt="Página de detalle de libro"
			title="Página de detalle de libro"
		>
	</p> 

	<p align="center">
		<img 
			width="49%" 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/profile-edit.png" 
			alt="Ajustes de perfil"
			title="Ajustes de perfil"
		>
		&nbsp;
		<img 
			width="49%" 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/author-detail.png" 
			alt="Página de detalle de autor"
			title="Página de detalle de autor"
		>
	</p> 

	<p align="center">
		<img 
			width="49%" 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/modal-add-content.png" 
			alt="Modal para añadir contenido (sólo para administrador)"
			title="Modal para añadir contenido (sólo para administrador)"
		>
		&nbsp;
		<img 
			width="49%" 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/users-index.png" 
			alt="Página de índice de usuarios (sólo para administrador)"
			title="Página de índice de usuarios (sólo para administrador)"
		>
	</p>

	<p align="center">
		<img 
			width="49%" 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/screenshots/book-read.png" 
			alt="Lector de libros"
			title="Lector de libros"
		>
	</p> 
	<br>
</details>

<!-- BUILT WITH -->

### Hecho Con

- [Laravel](https://github.com/laravel/laravel): Framework de PHP.
- [TailwindCSS](https://tailwindcss.com/docs/guides/laravel): Framework de CSS.
- [Vue.js 3](https://vuejs.org/): Framework de JavaScript.
- [Inertia.js](https://inertiajs.com/): Permite crear aplicaciones de una sola página totalmente renderizadas del lado del cliente sin la complejidad de las SPA modernas.
- [drawio.com](https://drawio.com): Herramienta utilizada para crear los diagramas del modelo entidad-relación.
- [dbdiagram.io](https://dbdiagram.io/): Herramienta utilizada para crear los diagramas del modelo relacional.
- [Flowbite](https://flowbite.com): Biblioteca de componentes para Tailwind CSS usuario de código abierto. En el proyecto, se utiliza para desplegables y pestañas (tabs).
- [PDF.js](https://mozilla.github.io/pdf.js/): Librería para renderizar PDFs.
- [Spatie/laravel-medialibrary](https://spatie.be/docs/laravel-medialibrary/v11/introduction): Asocia archivos con modelos Eloquent.
- [uuid](https://www.npmjs.com/package/uuid): Paquete JavaScript para generar UUID únicos para las alertas.
- [cviebrock/eloquent-sluggable](https://github.com/cviebrock/eloquent-sluggable): Crea slugs únicos para modelos Eloquent en Laravel.
- [jpkleemans/vite-svg-loader](https://github.com/jpkleemans/vite-svg-loader): Plugin de Vue para cargar archivos SVG como componentes de Vue.
- [laravel-validation-rules/credit-card](https://github.com/laravel-validation-rules/credit-card): Paquete de validación de tarjetas de crédito para Laravel.
- [@vueuse/core](https://github.com/vueuse/vueuse): Colección de utilidades de composición para Vue. En el proyecto, se utiliza para el desplazamiento infinito con `useIntersectionObserver` y `useDebounceFn`.
- [@vuepic/vue-datepicker](https://vue3datepicker.com/): Componente Datepicker para Vue 3.
- [xiCO2k/laravel-vue-i18n](https://github.com/xiCO2k/laravel-vue-i18n): I18n para Vue y Laravel.
- [Terms and Conditions generator](https://www.termsandconditionsgenerator.com): Autoexplicado.

<!-- ENTITY-RELATIONSHIP MODEL -->

### Modelo Entidad-Relación

<div align="center">
	<picture>
		<source 
			srcset="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/light/entity-relationship-diagram.svg"
			media="(prefers-color-scheme: light)"
		/>
		<source 
			srcset="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/dark/entity-relationship-diagram.svg"
			media="(prefers-color-scheme: dark)"
		/>
		<img 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/light/entity-relationship-diagram.svg"
			alt="Modelo Entidad-Relación"
		>
	</picture>
</div>

<!-- RELATIONAL MODEL -->

### Modelo Relacional

<div align="center">
	<picture>
		<source 
			srcset="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/common/light/relational-model.svg"
			media="(prefers-color-scheme: light)"
		/>
		<source 
			srcset="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/common/dark/relational-model.svg"
			media="(prefers-color-scheme: dark)"
		/>
		<img 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/common/light/relational-model.svg"
			alt="Modelo Relacional"
		>
	</picture>
</div>

<!-- USE CASES -->

### Casos de Uso

<div align="center">
	<picture>
		<source 
			srcset="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/light/use-cases.svg"
			media="(prefers-color-scheme: light)"
		/>
		<source 
			srcset="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/dark/use-cases.svg"
			media="(prefers-color-scheme: dark)"
		/>
		<img 
			src="https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/docs/es/light/use-cases.svg"
			alt="Casos de Uso"
		>
	</picture>
</div>

<p align="right">(<a href="#top">volver al principio</a>)</p>

<!-- GETTING STARTED -->

## Primeros Pasos

### Requisitos Previos

Asegúrate de que tienes PHP, Git, Composer, npm y Laravel instalados en tu máquina local. Si no es así, puedes descargarlos e instalarlos desde los sitios web oficiales:

- [PHP](https://www.php.net/downloads.php)
- [Git](https://git-scm.com/downloads)
- [Composer](https://getcomposer.org/download/)
- [npm](https://www.npmjs.com/package/download)
- [Laravel](https://laravel.com/docs/9.x/installation) (instálalo globalmente utilizando Composer)

### Pasos

1. Utiliza `git` para clonar el repositorio del proyecto en tu máquina local. Abre la terminal y ejecuta:
	 ```shell
	 git clone https://github.com/HenestrosaDev/the-wordsmiths-collection.git
	 ```
2. Navega al directorio del proyecto utilizando el comando `cd`. Por ejemplo:
	 ```shell
	 cd /path/to/the-wordsmiths-collection`
	 ```
3. Instala las dependencias de Composer ejecutando el siguiente comando:
	 ```shell
	 composer install
	 ```
4. Instala las dependencias npm ejecutando el siguiente comando:
	 ```shell
	 npm install
	 ```
5. Crea un archivo `.env`, ya que Laravel utiliza variables de entorno almacenadas en el archivo `.env` para la configuración. Duplica el archivo `.env.example` proporcionado con el proyecto y renómbralo a `.env`:
	 ```shell
	 cp .env.example .env
	 ```
	 Edítalo para establecer la configuración correcta de tu base de datos.<br>
	 <br>
6. Genera una clave de aplicación, necesaria por motivos de seguridad:
	 ```shell
	 php artisan key:generate
	 ```
7. Ejecuta las migraciones de la base de datos:
	 ```shell
	 php artisan migrate
	 ```
8. Inicia el servidor de desarrollo ejecutando este comando:
	 ```shell
	 php artisan serve
	 ```

Puedes habilitar **hot refresh** con Vite ejecutando el comando `npm run dev`. Ten en cuenta que necesitarás abrir la URL proporcionada por Artisan para disfrutar de esta característica, no la proporcionada por Vite.

<p align="right">(<a href="#top">volver al principio</a>)</p>

<!-- NOTES -->

## Notas

- Usa un correo real al crear una cuenta, ya que se mandará un correo de verificación para poder proceder con el pago.
- Usa un [generador de tarjetas de crédito](https://www.creditcardvalidator.org/generator) para fingir un pago.

<!-- ROADMAP -->

## Hoja de Ruta

- [ ] Soportar archivos `.mobi` y `.epub` y crea un visor para ellos para una experiencia más satisfactoria en dispositivos móviles.
- [ ] Añadir una página `User/Detail.vue` para ver datos como la actividad reciente, puntuaciones y comentarios de otros usuarios.
- [ ] Añadir soporte para medias puntuaciones (0,5, 1,5, 2,5, 3,5 y 4,5).
- [ ] Permitir a los usuarios subir una foto de perfil y cambiarla.
- [ ] Añadir la opción de añadir libros favoritos, géneros, autores y reseñas.
- [ ] Añadir un diálogo al pasar el ratón sobre un libro para mostrar más información sobre él, similar a [Filmin](https://filmin.es).
- [ ] Hacer de esto un producto real mediante el uso de una pasarela de pago como Redsys o Stripe.
- [ ] Añadir más planes de suscripción para soportar más tiempo a un precio reducido (por ejemplo, 6 meses de suscripción Premium por 34,99€).
- [ ] Añadir la lógica y los servicios faltantes para controlar el pago recurrente.
- [ ] Extender la fecha de finalización de la suscripción si el pago recurrente se realiza correctamente, o cancelar si el pago no se puede realizar.

Puedes proponer una nueva función creando una [incidencia](https://github.com/HenestrosaDev/the-wordsmiths-collection/new/choose).

<!-- AUTHORS -->

## Autores

- José Carlos López Henestrosa ([HenestrosaDev](https://github.com/HenestrosaDev))

<!-- LICENSE -->

## Licencia

Distribuido bajo la licencia MIT. Mira [`LICENSE`](https://github.com/HenestrosaDev/the-wordsmiths-collection/blob/main/.github/LICENSE) para más información.

<p align="right">(<a href="#top">volver al principio</a>)</p>
