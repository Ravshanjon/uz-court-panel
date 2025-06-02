<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Application')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Didact+Gothic&family=Exo+2:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>
<style>
    * {
        font-style: normal;
        font-size: 13px;
    }
</style>

<main class="grow">
    <nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="mx-auto flex flex-wrap items-center justify-between">
            <div class="w-full p-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button
                            class="mr-3 cursor-pointer rounded p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white lg:inline">
                            <span class="sr-only">Toggle sidebar</span>
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20"
                                 class="h-6 w-6" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <a class="flex items-center" href="/"><img alt=""
                                                                   src="https://flowbite.com/docs/images/logo.svg"
                                                                   class="mr-3 h-6 sm:h-8"><span
                                class="self-center whitespace-nowrap text-2xl font-semibold dark:text-white">Flowbite</span></a>
                        <form class="ml-16 hidden md:block"><label
                                class="text-sm font-medium text-gray-900 dark:text-gray-300 sr-only"
                                for="search">Search</label>
                            <div class="flex">
                                <div class="relative w-full">
                                    <div
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                             viewBox="0 0 20 20"
                                             class="h-5 w-5 text-gray-500 dark:text-gray-400" height="1em"
                                             width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input
                                        class="block w-full border disabled:cursor-not-allowed disabled:opacity-50 bg-gray-50 border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 pl-10 rounded-lg p-2.5 text-sm"
                                        id="search" name="search" placeholder="Search" required="" size="32"
                                        type="search"></div>
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center lg:gap-3">
                        <div class="flex items-center">
                            <button
                                class="cursor-pointer rounded p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:bg-gray-700 dark:focus:ring-gray-700 lg:hidden">
                                <span class="sr-only">Search</span>
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                     viewBox="0 0 20 20" class="h-6 w-6" height="1em" width="1em"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div class="w-fit dark:text-white" data-testid="flowbite-tooltip-target">
                                <button class="flex items-center"><span
                                        class="rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-700"><span
                                            class="sr-only">Notifications</span><svg stroke="currentColor"
                                                                                     fill="currentColor"
                                                                                     stroke-width="0"
                                                                                     viewBox="0 0 20 20"
                                                                                     class="text-2xl text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white "
                                                                                     height="1em" width="1em"
                                                                                     xmlns="http://www.w3.org/2000/svg"><path
                                                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg></span>
                                </button>
                            </div>
                            <div data-testid="flowbite-tooltip" tabindex="-1"
                                 class="z-10 w-fit rounded-xl divide-y divide-gray-100 shadow transition-opacity duration-100 invisible opacity-0 border border-gray-200 bg-white text-gray-900 dark:border-none dark:bg-gray-700 dark:text-white"
                                 id=":r0:" role="tooltip"
                                 style="position: absolute; top: 61px; left: 1506.56px;">
                                <div class="rounded-xl text-sm text-gray-700 dark:text-gray-200">
                                    <ul class="">
                                        <div class="max-w-[24rem]">
                                            <div
                                                class="block rounded-t-xl bg-gray-50 py-2 px-4 text-center text-base font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                                Notifications
                                            </div>
                                            <div><a href="#"
                                                    class="flex border-y py-3 px-4 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                    <div class="shrink-0"><img alt=""
                                                                               src="../images/users/bonnie-green.png"
                                                                               class="h-11 w-11 rounded-full">
                                                        <div
                                                            class="absolute -mt-5 ml-6 flex h-5 w-5 items-center justify-center rounded-full border border-white bg-primary-700 dark:border-gray-700">
                                                            <svg class="h-3 w-3 text-white" fill="currentColor"
                                                                 viewBox="0 0 20 20"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path>
                                                                <path
                                                                    d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="w-full pl-3">
                                                        <div
                                                            class="mb-1.5 text-sm font-normal text-gray-500 dark:text-gray-400">
                                                            New message from&nbsp;<span
                                                                class="font-semibold text-gray-900 dark:text-white">Bonnie Green</span>:
                                                            "Hey, what's up? All set for the presentation?"
                                                        </div>
                                                        <div
                                                            class="text-xs font-medium text-primary-700 dark:text-primary-400">
                                                            a few moments ago
                                                        </div>
                                                    </div>
                                                </a><a href="#"
                                                       class="flex border-b py-3 px-4 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                    <div class="shrink-0"><img alt=""
                                                                               src="../images/users/jese-leos.png"
                                                                               class="h-11 w-11 rounded-full">
                                                        <div
                                                            class="absolute -mt-5 ml-6 flex h-5 w-5 items-center justify-center rounded-full border border-white bg-gray-900 dark:border-gray-700">
                                                            <svg class="h-3 w-3 text-white" fill="currentColor"
                                                                 viewBox="0 0 20 20"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="w-full pl-3">
                                                        <div
                                                            class="mb-1.5 text-sm font-normal text-gray-500 dark:text-gray-400">
                                                                    <span
                                                                        class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>&nbsp;and&nbsp;<span
                                                                class="font-medium text-gray-900 dark:text-white">5 others</span>&nbsp;started
                                                            following you.
                                                        </div>
                                                        <div
                                                            class="text-xs font-medium text-primary-700 dark:text-primary-400">
                                                            10 minutes ago
                                                        </div>
                                                    </div>
                                                </a><a href="#"
                                                       class="flex border-b py-3 px-4 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                    <div class="shrink-0"><img alt=""
                                                                               src="../images/users/joseph-mcfall.png"
                                                                               class="h-11 w-11 rounded-full">
                                                        <div
                                                            class="absolute -mt-5 ml-6 flex h-5 w-5 items-center justify-center rounded-full border border-white bg-red-600 dark:border-gray-700">
                                                            <svg class="h-3 w-3 text-white" fill="currentColor"
                                                                 viewBox="0 0 20 20"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                      d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                                      clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="w-full pl-3">
                                                        <div
                                                            class="mb-1.5 text-sm font-normal text-gray-500 dark:text-gray-400">
                                                                    <span
                                                                        class="font-semibold text-gray-900 dark:text-white">Joseph Mcfall</span>&nbsp;and&nbsp;<span
                                                                class="font-medium text-gray-900 dark:text-white">141 others</span>&nbsp;love
                                                            your story. See it and view more stories.
                                                        </div>
                                                        <div
                                                            class="text-xs font-medium text-primary-700 dark:text-primary-400">
                                                            44 minutes ago
                                                        </div>
                                                    </div>
                                                </a><a href="#"
                                                       class="flex border-b py-3 px-4 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                    <div class="shrink-0"><img alt=""
                                                                               src="../images/users/leslie-livingston.png"
                                                                               class="h-11 w-11 rounded-full">
                                                        <div
                                                            class="absolute -mt-5 ml-6 flex h-5 w-5 items-center justify-center rounded-full border border-white bg-green-400 dark:border-gray-700">
                                                            <svg class="h-3 w-3 text-white" fill="currentColor"
                                                                 viewBox="0 0 20 20"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                      d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                                                                      clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="w-full pl-3">
                                                        <div
                                                            class="mb-1.5 text-sm font-normal text-gray-500 dark:text-gray-400">
                                                                    <span
                                                                        class="font-semibold text-gray-900 dark:text-white">Leslie Livingston</span>&nbsp;mentioned
                                                            you in a comment:&nbsp;<span
                                                                class="font-medium text-primary-700 dark:text-primary-500">@bonnie.green</span>&nbsp;what
                                                            do you say?
                                                        </div>
                                                        <div
                                                            class="text-xs font-medium text-primary-700 dark:text-primary-400">
                                                            1 hour ago
                                                        </div>
                                                    </div>
                                                </a><a href="#"
                                                       class="flex py-3 px-4 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <div class="shrink-0"><img alt=""
                                                                               src="../images/users/robert-brown.png"
                                                                               class="h-11 w-11 rounded-full">
                                                        <div
                                                            class="absolute -mt-5 ml-6 flex h-5 w-5 items-center justify-center rounded-full border border-white bg-purple-500 dark:border-gray-700">
                                                            <svg class="h-3 w-3 text-white" fill="currentColor"
                                                                 viewBox="0 0 20 20"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="w-full pl-3">
                                                        <div
                                                            class="mb-1.5 text-sm font-normal text-gray-500 dark:text-gray-400">
                                                                    <span
                                                                        class="font-semibold text-gray-900 dark:text-white">Robert Brown</span>&nbsp;posted
                                                            a new video: Glassmorphism - learn how to implement
                                                            the new design trend.
                                                        </div>
                                                        <div
                                                            class="text-xs font-medium text-primary-700 dark:text-primary-400">
                                                            3 hours ago
                                                        </div>
                                                    </div>
                                                </a></div>
                                            <a href="#"
                                               class="block rounded-b-xl bg-gray-50 py-2 text-center text-base font-normal text-gray-900 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:underline">
                                                <div class="inline-flex items-center gap-x-2">
                                                    <svg stroke="currentColor" fill="currentColor"
                                                         stroke-width="0" viewBox="0 0 20 20" class="h-6 w-6"
                                                         height="1em" width="1em"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                        <path fill-rule="evenodd"
                                                              d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                              clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span>View all</span></div>
                                            </a></div>
                                    </ul>
                                </div>
                            </div>
                            <div class="w-fit dark:text-white" data-testid="flowbite-tooltip-target">
                                <button class="flex items-center"><span
                                        class="rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-700"><span
                                            class="sr-only">Apps</span><svg stroke="currentColor"
                                                                            fill="currentColor" stroke-width="0"
                                                                            viewBox="0 0 20 20"
                                                                            class="text-2xl text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                                                            height="1em" width="1em"
                                                                            xmlns="http://www.w3.org/2000/svg"><path
                                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></span>
                                </button>
                            </div>
                            <div data-testid="flowbite-tooltip" tabindex="-1"
                                 class="z-10 w-fit rounded-xl divide-y divide-gray-100 shadow transition-opacity duration-100 invisible opacity-0 border border-gray-200 bg-white text-gray-900 dark:border-none dark:bg-gray-700 dark:text-white"
                                 id=":r2:" role="tooltip" style="position: absolute; top: 61px; left: 1617px;">
                                <div class="rounded-xl text-sm text-gray-700 dark:text-gray-200">
                                    <ul class="">
                                        <div
                                            class="block rounded-t-lg border-b bg-gray-50 py-2 px-4 text-center text-base font-medium text-gray-700 dark:border-b-gray-600 dark:bg-gray-700 dark:text-white">
                                            Apps
                                        </div>
                                        <div class="grid grid-cols-3 gap-4 p-4"><a href="#"
                                                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Sales
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Users
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Inbox
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Profile
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Settings
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                                    <path fill-rule="evenodd"
                                                          d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Products
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                    <path fill-rule="evenodd"
                                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Pricing
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="none" stroke-width="0"
                                                     viewBox="0 0 24 24"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Billing
                                                </div>
                                            </a><a href="#"
                                                   class="block rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                     viewBox="0 0 20 20"
                                                     class="mx-auto mb-1 h-7 w-7 text-gray-500 dark:text-white"
                                                     height="1em" width="1em"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Logout
                                                </div>
                                            </a></div>
                                    </ul>
                                </div>
                            </div>
                            <button
                                class="rounded-lg p-2.5 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                                data-testid="dark-theme-toggle" type="button" aria-label="Toggle dark mode">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                     viewBox="0 0 20 20" aria-label="Currently light mode" class="h-5 w-5"
                                     height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-fit dark:text-white" data-testid="flowbite-tooltip-target">
                                <button class="flex items-center"><span><span class="sr-only">User menu</span><div
                                            class="flex justify-center items-center space-x-4"
                                            data-testid="flowbite-avatar"><div class="relative"><img alt=""
                                                                                                     class="!rounded-full rounded w-8 h-8 rounded"
                                                                                                     data-testid="flowbite-avatar-img"
                                                                                                     src="../images/users/neil-sims.png"></div></div></span>
                                </button>
                            </div>
                            <div data-testid="flowbite-tooltip" tabindex="-1"
                                 class="z-10 w-fit rounded-xl divide-y divide-gray-100 shadow transition-opacity duration-100 invisible opacity-0 border border-gray-200 bg-white text-gray-900 dark:border-none dark:bg-gray-700 dark:text-white"
                                 id=":r4:" role="tooltip"
                                 style="position: absolute; top: 57px; left: 1691.33px;">
                                <div class="rounded-xl text-sm text-gray-700 dark:text-gray-200">
                                    <ul class="">
                                        <div class="block py-2 px-4 text-sm text-gray-700 dark:text-gray-200">
                                            <span class="block text-sm">Neil Sims</span><span
                                                class="block truncate text-sm font-medium">neil.sims@flowbite.com</span>
                                        </div>
                                        <div class="my-1 h-px bg-gray-100 dark:bg-gray-600"></div>
                                        <li class="flex items-center justify-start py-2 px-4 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Dashboard
                                        </li>
                                        <li class="flex items-center justify-start py-2 px-4 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Settings
                                        </li>
                                        <li class="flex items-center justify-start py-2 px-4 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Earnings
                                        </li>
                                        <div class="my-1 h-px bg-gray-100 dark:bg-gray-600"></div>
                                        <li class="flex items-center justify-start py-2 px-4 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Sign out
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="flex items-start pt-16">
        <div class="lg:!block hidden">
            <aside aria-label="Sidebar with multi-level dropdown example"
                   class="flex fixed top-0 left-0 z-20 flex-col flex-shrink-0 pt-16 h-full duration-75 border-r border-gray-200 lg:flex transition-width dark:border-gray-700 w-64">
                <div class="h-full overflow-y-auto overflow-x-hidden rounded bg-white py-4 px-3 dark:bg-gray-800">
                    <div class="flex h-full flex-col justify-between py-2">
                        <div>
                            <form class="pb-3 md:hidden">
                                <div class="flex">
                                    <div class="relative w-full">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20"
                                                 class="h-5 w-5 text-gray-500 dark:text-gray-400" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <input
                                            class="block w-full border disabled:cursor-not-allowed disabled:opacity-50 bg-gray-50 border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500 pl-10 rounded-lg p-2.5 text-sm"
                                            type="search" placeholder="Search" required="" size="32"></div>
                                </div>
                            </form>
                            <div class="" data-testid="flowbite-sidebar-items">
                                <ul class="mt-4 space-y-2 border-t border-gray-200 pt-4 first:mt-0 first:border-t-0 first:pt-0 dark:border-gray-700"
                                    data-testid="flowbite-sidebar-item-group">
                                    <li><a aria-labelledby="flowbite-sidebar-item-:r6:"
                                           class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                           href="/">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-item-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                            </svg>
                                            <span class="px-3 flex-1 whitespace-nowrap"
                                                  data-testid="flowbite-sidebar-item-content"
                                                  id="flowbite-sidebar-item-:r6:">Dashboard</span></a></li>
                                    <li><a aria-labelledby="flowbite-sidebar-item-:r7:"
                                           class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                           href="/kanban">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-item-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                            </svg>
                                            <span class="px-3 flex-1 whitespace-nowrap"
                                                  data-testid="flowbite-sidebar-item-content"
                                                  id="flowbite-sidebar-item-:r7:">Kanban</span></a></li>
                                    <li><a aria-labelledby="flowbite-sidebar-item-:r8:"
                                           class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                           href="/mailing/inbox">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-item-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path>
                                                <path
                                                    d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                                            </svg>
                                            <span class="px-3 flex-1 whitespace-nowrap"
                                                  data-testid="flowbite-sidebar-item-content"
                                                  id="flowbite-sidebar-item-:r8:">Inbox</span><span
                                                class="flex h-fit items-center gap-1 font-semibold bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-800 group-hover:bg-blue-200 dark:group-hover:bg-blue-300 rounded-full px-2 py-1 p-1 text-xs"
                                                data-testid="flowbite-sidebar-label"><span>3</span></span></a>
                                    </li>
                                    <li>
                                        <button
                                            class="group flex w-full items-center rounded-lg p-2 text-base font-medium text-gray-900 transition duration-75 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                            id="flowbite-sidebar-collapse-:r9:" title="E-commerce"
                                            type="button">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-collapse-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="ml-3 flex-1 whitespace-nowrap text-left"
                                                  data-testid="flowbite-sidebar-collapse-label">E-commerce</span>
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true" class="h-6 w-6"
                                                 height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        <ul aria-labelledby="flowbite-sidebar-collapse-:r9:"
                                            class="space-y-2 py-2" hidden="">
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r24:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/e-commerce/products"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r24:">Products</span></a>
                                            </li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r25:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/e-commerce/billing"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r25:">Billing</span></a></li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r26:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/e-commerce/invoice"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r26:">Invoice</span></a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <button
                                            class="group flex w-full items-center rounded-lg p-2 text-base font-medium text-gray-900 transition duration-75 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                            id="flowbite-sidebar-collapse-:rd:" title="Users" type="button">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white text-gray-900"
                                                 data-testid="flowbite-sidebar-collapse-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                            </svg>
                                            <span class="ml-3 flex-1 whitespace-nowrap text-left"
                                                  data-testid="flowbite-sidebar-collapse-label">Users</span>
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true" class="h-6 w-6"
                                                 height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        <ul aria-labelledby="flowbite-sidebar-collapse-:rd:"
                                            class="space-y-2 py-2">
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1n:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75 bg-gray-100 dark:bg-gray-700"
                                                   href="/users/list"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r1n:">Users list</span></a>
                                            </li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1o:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/users/profile"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r1o:">Profile</span></a></li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1p:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/users/feed"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r1p:">Feed</span></a></li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1q:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/users/settings"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r1q:">Settings</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <button
                                            class="group flex w-full items-center rounded-lg p-2 text-base font-medium text-gray-900 transition duration-75 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                            id="flowbite-sidebar-collapse-:ri:" title="Pages" type="button">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-collapse-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm9 4a1 1 0 10-2 0v6a1 1 0 102 0V7zm-3 2a1 1 0 10-2 0v4a1 1 0 102 0V9zm-3 3a1 1 0 10-2 0v1a1 1 0 102 0v-1z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="ml-3 flex-1 whitespace-nowrap text-left"
                                                  data-testid="flowbite-sidebar-collapse-label">Pages</span>
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true" class="h-6 w-6"
                                                 height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        <ul aria-labelledby="flowbite-sidebar-collapse-:ri:"
                                            class="space-y-2 py-2" hidden="">
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1r:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/pages/pricing"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r1r:">Pricing</span></a></li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1s:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/pages/maintenance"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r1s:">Maintenace</span></a>
                                            </li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1t:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/pages/404"><span class="px-3 flex-1 whitespace-nowrap"
                                                                           data-testid="flowbite-sidebar-item-content"
                                                                           id="flowbite-sidebar-item-:r1t:">404 not found</span></a>
                                            </li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1u:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/pages/500"><span class="px-3 flex-1 whitespace-nowrap"
                                                                           data-testid="flowbite-sidebar-item-content"
                                                                           id="flowbite-sidebar-item-:r1u:">500 server error</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <button
                                            class="group flex w-full items-center rounded-lg p-2 text-base font-medium text-gray-900 transition duration-75 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                            id="flowbite-sidebar-collapse-:rn:" title="Authentication"
                                            type="button">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-collapse-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="ml-3 flex-1 whitespace-nowrap text-left"
                                                  data-testid="flowbite-sidebar-collapse-label">Authentication</span>
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true" class="h-6 w-6"
                                                 height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        <ul aria-labelledby="flowbite-sidebar-collapse-:rn:"
                                            class="space-y-2 py-2" hidden="">
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r1v:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/authentication/sign-in"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r1v:">Sign in</span></a></li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r20:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/authentication/sign-up"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r20:">Sign up</span></a></li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r21:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/authentication/forgot-password"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r21:">Forgot password</span></a>
                                            </li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r22:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/authentication/reset-password"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r22:">Reset password</span></a>
                                            </li>
                                            <li><a aria-labelledby="flowbite-sidebar-item-:r23:"
                                                   class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 group w-full pl-8 transition duration-75"
                                                   href="/authentication/profile-lock"><span
                                                        class="px-3 flex-1 whitespace-nowrap"
                                                        data-testid="flowbite-sidebar-item-content"
                                                        id="flowbite-sidebar-item-:r23:">Profile lock</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <ul class="mt-4 space-y-2 border-t border-gray-200 pt-4 first:mt-0 first:border-t-0 first:pt-0 dark:border-gray-700"
                                    data-testid="flowbite-sidebar-item-group">
                                    <li><a aria-labelledby="flowbite-sidebar-item-:rt:"
                                           class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                           href="https://github.com/themesberg/flowbite-react/">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-item-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path>
                                                <path
                                                    d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path>
                                            </svg>
                                            <span class="px-3 flex-1 whitespace-nowrap"
                                                  data-testid="flowbite-sidebar-item-content"
                                                  id="flowbite-sidebar-item-:rt:">Docs</span></a></li>
                                    <li><a aria-labelledby="flowbite-sidebar-item-:ru:"
                                           class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                           href="https://flowbite-react.com/">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-item-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                            </svg>
                                            <span class="px-3 flex-1 whitespace-nowrap"
                                                  data-testid="flowbite-sidebar-item-content"
                                                  id="flowbite-sidebar-item-:ru:">Components</span></a></li>
                                    <li><a aria-labelledby="flowbite-sidebar-item-:rv:"
                                           class="flex items-center justify-center rounded-lg p-2 text-base font-medium text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                           href="https://github.com/themesberg/flowbite-react/issues">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                                 viewBox="0 0 20 20" aria-hidden="true"
                                                 class="h-6 w-6 flex-shrink-0 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                                 data-testid="flowbite-sidebar-item-icon" height="1em"
                                                 width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="px-3 flex-1 whitespace-nowrap"
                                                  data-testid="flowbite-sidebar-item-content"
                                                  id="flowbite-sidebar-item-:rv:">Help</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex items-center justify-center gap-x-5">
                            <button class="rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-700"><span
                                    class="sr-only">Tweaks</span>
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                     viewBox="0 0 20 20"
                                     class="text-2xl text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white "
                                     height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path>
                                </svg>
                            </button>
                            <div>
                                <div class="w-fit" data-testid="flowbite-tooltip-target"><a
                                        href="/users/settings"
                                        class="inline-flex cursor-pointer justify-center rounded p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"><span
                                            class="sr-only">Settings page</span>
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                             viewBox="0 0 20 20"
                                             class="text-2xl text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                             height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </a></div>
                                <div data-testid="flowbite-tooltip" tabindex="-1"
                                     class="absolute inline-block z-10 rounded-lg py-2 px-3 text-sm font-medium shadow-sm transition-opacity duration-300 invisible opacity-0 bg-gray-900 text-white dark:bg-gray-700"
                                     id=":r10:" role="tooltip"
                                     style="position: absolute; top: 893px; left: 63.8515px;">
                                    <div class="relative z-20">Settings page</div>
                                    <div class="absolute z-10 h-2 w-2 rotate-45 bg-gray-900 dark:bg-gray-700"
                                         data-testid="flowbite-tooltip-arrow" style="bottom: -4px; left: 54px;">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="w-fit dark:text-white" data-testid="flowbite-tooltip-target">
                                    <button class="flex items-center"><span
                                            class="inline-flex cursor-pointer justify-center rounded p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white"><span
                                                class="sr-only">Current language</span><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                viewBox="0 0 3900 3900" class="h-5 w-5 rounded-full"><path
                                                    fill="#b22234" d="M0 0h7410v3900H0z"></path><path
                                                    d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0"
                                                    stroke="#fff" stroke-width="300"></path><path fill="#3c3b6e"
                                                                                                  d="M0 0h2964v2100H0z"></path><g
                                                    fill="#fff"><g id="d"><g id="c"><g id="e"><g id="b"><path
                                                                        id="a"
                                                                        d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"></path><use
                                                                        xlink:href="#a" y="420"></use><use
                                                                        xlink:href="#a" y="840"></use><use
                                                                        xlink:href="#a" y="1260"></use></g><use
                                                                    xlink:href="#a" y="1680"></use></g><use
                                                                xlink:href="#b" x="247" y="210"></use></g><use
                                                            xlink:href="#c" x="494"></use></g><use
                                                        xlink:href="#d" x="988"></use><use xlink:href="#c"
                                                                                           x="1976"></use><use
                                                        xlink:href="#e" x="2470"></use></g></svg></span>
                                    </button>
                                </div>
                                <div data-testid="flowbite-tooltip" tabindex="-1"
                                     class="z-10 w-fit rounded-xl divide-y divide-gray-100 shadow transition-opacity duration-100 invisible opacity-0 border border-gray-200 bg-white text-gray-900 dark:border-none dark:bg-gray-700 dark:text-white"
                                     id=":r12:" role="tooltip"
                                     style="position: absolute; top: 765px; left: 162px;">
                                    <div class="rounded-xl text-sm text-gray-700 dark:text-gray-200">
                                        <ul class="">
                                            <ul class="py-1" role="none">
                                                <li><a href="#"
                                                       class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <div class="inline-flex items-center">
                                                            <svg class="mr-2 h-4 w-4 rounded-full"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 id="flag-icon-css-us" viewBox="0 0 512 512">
                                                                <g fill-rule="evenodd">
                                                                    <g stroke-width="1pt">
                                                                        <path fill="#bd3d44"
                                                                              d="M0 0h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0z"
                                                                              transform="scale(3.9385)"></path>
                                                                        <path fill="#fff"
                                                                              d="M0 10h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0z"
                                                                              transform="scale(3.9385)"></path>
                                                                    </g>
                                                                    <path fill="#192f5d" d="M0 0h98.8v70H0z"
                                                                          transform="scale(3.9385)"></path>
                                                                    <path fill="#fff"
                                                                          d="M8.2 3l1 2.8H12L9.7 7.5l.9 2.7-2.4-1.7L6 10.2l.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7L74 8.5l-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 7.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 24.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 21.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 38.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 35.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 52.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 49.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 66.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 63.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9z"
                                                                          transform="scale(3.9385)"></path>
                                                                </g>
                                                            </svg>
                                                            <span class="whitespace-nowrap">English (US)</span>
                                                        </div>
                                                    </a></li>
                                                <li><a href="#"
                                                       class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <div class="inline-flex items-center">
                                                            <svg class="mr-2 h-4 w-4 rounded-full"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 id="flag-icon-css-de" viewBox="0 0 512 512">
                                                                <path fill="#ffce00"
                                                                      d="M0 341.3h512V512H0z"></path>
                                                                <path d="M0 0h512v170.7H0z"></path>
                                                                <path fill="#d00"
                                                                      d="M0 170.7h512v170.6H0z"></path>
                                                            </svg>
                                                            Deutsch
                                                        </div>
                                                    </a></li>
                                                <li><a href="#"
                                                       class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <div class="inline-flex items-center">
                                                            <svg class="mr-2 h-4 w-4 rounded-full"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 id="flag-icon-css-it" viewBox="0 0 512 512">
                                                                <g fill-rule="evenodd" stroke-width="1pt">
                                                                    <path fill="#fff"
                                                                          d="M0 0h512v512H0z"></path>
                                                                    <path fill="#009246"
                                                                          d="M0 0h170.7v512H0z"></path>
                                                                    <path fill="#ce2b37"
                                                                          d="M341.3 0H512v512H341.3z"></path>
                                                                </g>
                                                            </svg>
                                                            Italiano
                                                        </div>
                                                    </a></li>
                                                <li><a href="#"
                                                       class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <div class="inline-flex items-center">
                                                            <svg class="mr-2 h-4 w-4 rounded-full"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                 id="flag-icon-css-cn" viewBox="0 0 512 512">
                                                                <defs>
                                                                    <path id="a" fill="#ffde00"
                                                                          d="M1-.3L-.7.8 0-1 .6.8-1-.3z"></path>
                                                                </defs>
                                                                <path fill="#de2910" d="M0 0h512v512H0z"></path>
                                                                <use width="30" height="20"
                                                                     transform="matrix(76.8 0 0 76.8 128 128)"
                                                                     xlink:href="#a"></use>
                                                                <use width="30" height="20"
                                                                     transform="rotate(-121 142.6 -47) scale(25.5827)"
                                                                     xlink:href="#a"></use>
                                                                <use width="30" height="20"
                                                                     transform="rotate(-98.1 198 -82) scale(25.6)"
                                                                     xlink:href="#a"></use>
                                                                <use width="30" height="20"
                                                                     transform="rotate(-74 272.4 -114) scale(25.6137)"
                                                                     xlink:href="#a"></use>
                                                                <use width="30" height="20"
                                                                     transform="matrix(16 -19.968 19.968 16 256 230.4)"
                                                                     xlink:href="#a"></use>
                                                            </svg>
                                                            <span class="whitespace-nowrap">中文 (繁體)</span>
                                                        </div>
                                                    </a></li>
                                            </ul>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
        <main class="overflow-y-auto relative w-full h-full bg-gray-50 dark:bg-gray-900 lg:ml-64">
            @yield('content')
        </main>
    </div>
</main>
</body>
</html>
