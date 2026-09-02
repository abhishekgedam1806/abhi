@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 

<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Job Seekers & Candidates')]) 
<!-- Inner Page Title end -->

@include('flash::message')

<!-- Modern Search Form Header -->
<div class="user-list-search-wrapper" style="background: #FFFFFF; border-bottom: 1.5px solid #E2E8F0; padding: 24px 0 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
    <div class="container" style="max-width: 1300px;">
        
        <!-- Search Type Tabs (Jobs vs Candidates) -->
        <div style="display: flex; gap: 8px; margin-bottom: 16px;">
            <a href="{{ route('job.list') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; background: #F1F5F9; color: #475569; font-size: 13px; font-weight: 700; text-decoration: none; border: 1px solid #CBD5E1;">
                <i class="fa fa-briefcase"></i> {{__('Search Jobs')}}
            </a>
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; border: 1px solid #2563EB;">
                <i class="fa fa-users"></i> {{__('Search Candidates')}}
            </span>
        </div>

        <form action="{{route('job.seeker.list')}}" method="get">
            <div class="searchform-container" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                
                <!-- Keyword Input -->
                <div style="flex: 2; min-width: 220px;">
                    <input type="text" name="search" value="{{ $search ?? request('search', '') }}" class="form-control" style="height: 44px !important; padding: 0 16px !important; border-radius: 10px !important; border: 1.5px solid #CBD5E1 !important; font-size: 13.5px !important; background: #FFFFFF !important;" placeholder="{{__('Skills, job title or candidate name...')}}" />
                </div>

                <!-- Functional Area -->
                <div style="flex: 1.5; min-width: 180px;">
                    {!! Form::select('functional_area_id[]', ['' => __('All Functional Areas')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id', 'style'=>'height: 44px; border-radius: 10px; border: 1.5px solid #CBD5E1; font-size: 13.5px;')) !!}
                </div>

                <!-- Country -->
                @if((bool)$siteSetting->country_specific_site)
                    {!! Form::hidden('country_id[]', Request::get('country_id[]', $siteSetting->default_country_id), array('id'=>'country_id')) !!}
                @else
                    <div style="flex: 1.2; min-width: 150px;">
                        {!! Form::select('country_id[]', ['' => __('All Countries')]+$countries, Request::get('country_id', $siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id', 'style'=>'height: 44px; border-radius: 10px; border: 1.5px solid #CBD5E1; font-size: 13.5px;')) !!}
                    </div>
                @endif

                <!-- Search Button -->
                <div style="flex-shrink: 0;">
                    <button type="submit" class="btn" style="height: 44px; padding: 0 22px; background: #2563EB; color: #FFFFFF; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                        <i class="fa fa-search"></i>
                        <span>{{__('Search')}}</span>
                    </button>
                </div>

                <!-- Post Job Action for Employers -->
                @if(Auth::guard('company')->check())
                    <div style="flex-shrink: 0; margin-left: auto;">
                        <a href="{{ route('post.job') }}" style="height: 44px; padding: 0 18px; background: #03855c; color: #FFFFFF; font-size: 13.5px; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; box-shadow: 0 2px 8px rgba(3,133,92,0.25);">
                            <i class="fa fa-plus-circle"></i>
                            <span>{{__('Post a Job')}}</span>
                        </a>
                    </div>
                @endif

            </div>
        </form>
    </div>
</div>

<div class="listpgWraper candidate-list-redesign" style="background: #F8FAFC; padding: 32px 0 60px; min-height: 85vh;">
    <div class="container" style="max-width: 1300px;">
        
        <form action="{{route('job.seeker.list')}}" method="get">
            <div class="row">
                
                <!-- Left Sidebar Filters -->
                <div class="col-lg-3 col-md-4">
                    <div class="candidate-filters-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
                            <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-filter" style="color: #2563EB;"></i>
                                <span>{{__('Filters')}}</span>
                            </h3>
                            <a href="{{ route('job.seeker.list') }}" style="font-size: 12px; font-weight: 700; color: #DC2626; text-decoration: none;">
                                {{__('Clear All')}}
                            </a>
                        </div>

                        <input type="hidden" name="search" value="{{Request::get('search', '')}}"/>

                        <!-- By Country Filter -->
                        @if(isset($countryIdsArray) && count($countryIdsArray))
                            <div class="filter-group" style="margin-bottom: 18px;">
                                <h4 style="font-size: 13.5px; font-weight: 800; color: #1E293B; margin: 0 0 8px 0;">{{__('Country')}}</h4>
                                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px; max-height: 160px; overflow-y: auto;">
                                    @foreach($countryIdsArray as $country_id)
                                        @php $country = App\Country::where('country_id','=',$country_id)->lang()->active()->first(); @endphp
                                        @if(null !== $country)
                                            @php $checked = (in_array($country->country_id, Request::get('country_id', array()))) ? 'checked="checked"' : ''; @endphp
                                            <li style="display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #475569;">
                                                <label style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer;">
                                                    <input type="checkbox" name="country_id[]" value="{{$country->country_id}}" {{$checked}} onchange="this.form.submit();">
                                                    <span>{{$country->country}}</span>
                                                </label>
                                                <span style="font-size: 11px; color: #94A3B8; font-weight: 600;">{{App\User::countNumJobSeekers('country_id', $country->country_id)}}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- By Career Level Filter -->
                        @if(isset($careerLevelIdsArray) && count($careerLevelIdsArray))
                            <div class="filter-group" style="margin-bottom: 18px;">
                                <h4 style="font-size: 13.5px; font-weight: 800; color: #1E293B; margin: 0 0 8px 0;">{{__('Career Level')}}</h4>
                                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px;">
                                    @foreach($careerLevelIdsArray as $cLevelId)
                                        @php $cLevel = App\CareerLevel::where('career_level_id','=',$cLevelId)->lang()->active()->first(); @endphp
                                        @if(null !== $cLevel)
                                            @php $checked = (in_array($cLevel->career_level_id, Request::get('career_level_id', array()))) ? 'checked="checked"' : ''; @endphp
                                            <li style="display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #475569;">
                                                <label style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer;">
                                                    <input type="checkbox" name="career_level_id[]" value="{{$cLevel->career_level_id}}" {{$checked}} onchange="this.form.submit();">
                                                    <span>{{$cLevel->career_level}}</span>
                                                </label>
                                                <span style="font-size: 11px; color: #94A3B8; font-weight: 600;">{{App\User::countNumJobSeekers('career_level_id', $cLevel->career_level_id)}}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- By Experience Filter -->
                        @if(isset($jobExperienceIdsArray) && count($jobExperienceIdsArray))
                            <div class="filter-group" style="margin-bottom: 18px;">
                                <h4 style="font-size: 13.5px; font-weight: 800; color: #1E293B; margin: 0 0 8px 0;">{{__('Experience')}}</h4>
                                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px;">
                                    @foreach($jobExperienceIdsArray as $jExpId)
                                        @php $jExp = App\JobExperience::where('job_experience_id','=',$jExpId)->lang()->active()->first(); @endphp
                                        @if(null !== $jExp)
                                            @php $checked = (in_array($jExp->job_experience_id, Request::get('job_experience_id', array()))) ? 'checked="checked"' : ''; @endphp
                                            <li style="display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #475569;">
                                                <label style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer;">
                                                    <input type="checkbox" name="job_experience_id[]" value="{{$jExp->job_experience_id}}" {{$checked}} onchange="this.form.submit();">
                                                    <span>{{$jExp->job_experience}}</span>
                                                </label>
                                                <span style="font-size: 11px; color: #94A3B8; font-weight: 600;">{{App\User::countNumJobSeekers('job_experience_id', $jExp->job_experience_id)}}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- By Gender Filter -->
                        @if(isset($genderIdsArray) && count($genderIdsArray))
                            <div class="filter-group">
                                <h4 style="font-size: 13.5px; font-weight: 800; color: #1E293B; margin: 0 0 8px 0;">{{__('Gender')}}</h4>
                                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px;">
                                    @foreach($genderIdsArray as $gId)
                                        @php $gender = App\Gender::where('gender_id','=',$gId)->lang()->active()->first(); @endphp
                                        @if(null !== $gender)
                                            @php $checked = (in_array($gender->gender_id, Request::get('gender_id', array()))) ? 'checked="checked"' : ''; @endphp
                                            <li style="display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #475569;">
                                                <label style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer;">
                                                    <input type="checkbox" name="gender_id[]" value="{{$gender->gender_id}}" {{$checked}} onchange="this.form.submit();">
                                                    <span>{{$gender->gender}}</span>
                                                </label>
                                                <span style="font-size: 11px; color: #94A3B8; font-weight: 600;">{{App\User::countNumJobSeekers('gender_id', $gender->gender_id)}}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Right Candidate Results Column -->
                <div class="col-lg-9 col-md-8">
                    
                    <!-- Search Query Result Header Bar -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h2 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0;">
                                {{__('Job Seekers & Candidates')}}
                            </h2>
                            <span style="font-size: 13px; color: #64748B;">
                                {{__('Showing')}} <strong>{{ $jobSeekers->total() }}</strong> {{__('candidates')}}
                                @if(!empty($search ?? request('search')))
                                    {{__('for')}} "<strong>{{ $search ?? request('search') }}</strong>"
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Matching Job Openings Alert Banner (If search query matches jobs) -->
                    @if(!empty($search ?? request('search')))
                        @php
                            $searchQuery = $search ?? request('search');
                            $matchJobs = \App\Job::where('is_active', 1)
                                ->where(function($q) use ($searchQuery) {
                                    $q->where('title', 'like', "%{$searchQuery}%")
                                      ->orWhere('description', 'like', "%{$searchQuery}%");
                                })
                                ->with('company')
                                ->take(3)
                                ->get();
                        @endphp

                        @if($matchJobs->count() > 0)
                            <div class="jobs-match-alert-box" style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 14px rgba(37,99,235,0.06);">
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 14px; margin-bottom: 12px; flex-wrap: wrap;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #2563EB; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                            <i class="fa fa-briefcase"></i>
                                        </div>
                                        <div>
                                            <h4 style="font-size: 15.5px; font-weight: 800; color: #0F172A; margin: 0;">
                                                {{__('Looking for Job Openings')}}?
                                            </h4>
                                            <span style="font-size: 13px; color: #334155;">
                                                {{__('We found')}} <strong>{{ $matchJobs->count() }}+ {{__('matching jobs')}}</strong> {{__('for')}} "<strong>{{ $searchQuery }}</strong>"
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('job.list', ['search' => $searchQuery]) }}" style="background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; padding: 9px 18px; border-radius: 8px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                                        {{__('View All Matching Jobs')}} &rarr;
                                    </a>
                                </div>

                                <!-- Quick Job Cards Preview -->
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
                                    @foreach($matchJobs as $mJob)
                                        <a href="{{ route('job.detail', [$mJob->slug]) }}" style="background: #FFFFFF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 12px 14px; text-decoration: none; display: block; transition: all 0.15s ease;">
                                            <div style="font-size: 13.5px; font-weight: 800; color: #0F172A; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $mJob->title }}
                                            </div>
                                            <div style="font-size: 12px; color: #2563EB; font-weight: 600;">
                                                {{ $mJob->getCompany() ? $mJob->getCompany()->name : 'Employer' }}
                                            </div>
                                            <div style="font-size: 11.5px; color: #64748B; margin-top: 4px;">
                                                <i class="fa fa-map-marker text-danger"></i> {{ $mJob->getCity('city') ?: 'Nagpur' }}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Candidates Results List -->
                    @if(isset($jobSeekers) && count($jobSeekers))
                        <div class="candidate-cards-list" style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($jobSeekers as $seeker)
                                @php
                                    $summary = $seeker->getProfileSummary('summary');
                                    $skills = $seeker->profileSkills()->with('jobSkill')->take(5)->get();
                                    $experience = $seeker->profileExperience()->first();
                                @endphp
                                <div class="candidate-item-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: all 0.15s ease;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                                        
                                        <!-- Candidate Info Left -->
                                        <div style="display: flex; gap: 18px; align-items: flex-start; flex: 1; min-width: 260px;">
                                            
                                            <!-- Avatar with Initial / Image -->
                                            <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; background: #EEF2F6; border: 2px solid #E2E8F0; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: #2563EB; flex-shrink: 0;">
                                                @if(!empty($seeker->image))
                                                    {{ $seeker->printUserImage(60, 60) }}
                                                @else
                                                    {{ strtoupper(substr($seeker->getName(), 0, 1)) }}
                                                @endif
                                            </div>

                                            <div style="flex: 1;">
                                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
                                                    <h3 style="font-size: 16.5px; font-weight: 800; color: #0F172A; margin: 0;">
                                                        <a href="{{route('user.profile', $seeker->id)}}" style="color: #0F172A; text-decoration: none;">
                                                            {{ $seeker->getName() }}
                                                        </a>
                                                    </h3>
                                                    <span style="display: inline-flex; align-items: center; justify-content: center; background: #03855c; color: #FFFFFF; width: 15px; height: 15px; border-radius: 50%; font-size: 9px; font-weight: bold;">
                                                        &#10003;
                                                    </span>
                                                </div>

                                                <div style="font-size: 13.5px; font-weight: 700; color: #2563EB; margin-bottom: 6px;">
                                                    {{ $experience ? $experience->title : ($seeker->getFunctionalArea('functional_area') ?: __('Candidate')) }}
                                                </div>

                                                <div style="display: flex; align-items: center; gap: 14px; font-size: 12.5px; color: #64748B; margin-bottom: 10px; flex-wrap: wrap;">
                                                    <span><i class="fa fa-map-marker text-danger"></i> {{ $seeker->getLocation() ?: __('Location Not Specified') }}</span>
                                                    <span><i class="fa fa-briefcase"></i> {{ $seeker->getJobExperience('job_experience') ?: 'Fresh / Any' }}</span>
                                                    <span><i class="fa fa-graduation-cap"></i> {{ $seeker->getCareerLevel('career_level') ?: 'Entry Level' }}</span>
                                                </div>

                                                @if(!empty($summary))
                                                    <p style="font-size: 13.5px; color: #475569; line-height: 1.5; margin-bottom: 12px;">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($summary), 160, '...') }}
                                                    </p>
                                                @endif

                                                <!-- Skill Tags -->
                                                @if(count($skills))
                                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                                        @foreach($skills as $sk)
                                                            @if($sk->getJobSkill())
                                                                <span style="background: #EFF6FF; border: 1px solid #DBEAFE; color: #1E40AF; font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">
                                                                    {{ $sk->getJobSkill('job_skill') }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                        <!-- Right Action Button -->
                                        <div style="flex-shrink: 0;">
                                            <a href="{{route('user.profile', $seeker->id)}}" style="display: inline-flex; align-items: center; gap: 6px; background: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; padding: 9px 18px; border-radius: 8px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.25); transition: all 0.15s ease;">
                                                <i class="fa fa-user"></i>
                                                <span>{{__('View Profile')}}</span>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div style="margin-top: 24px;">
                            {{ $jobSeekers->appends(request()->query())->links() }}
                        </div>

                    @else
                        <!-- Modern Empty State with Fallback Search in Jobs -->
                        <div class="empty-candidate-state" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 48px 24px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                            <div style="width: 64px; height: 64px; border-radius: 50%; background: #EFF6FF; color: #2563EB; font-size: 26px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                <i class="fa fa-search"></i>
                            </div>
                            <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">
                                {{__('No Candidates Found')}}
                            </h3>
                            <p style="font-size: 14px; color: #64748B; max-width: 480px; margin: 0 auto 20px;">
                                @if(!empty($search ?? request('search')))
                                    {{__('We could not find any candidate profiles matching')}} "<strong>{{ $search ?? request('search') }}</strong>".
                                @else
                                    {{__('No candidates match your selected filters.')}}
                                @endif
                            </p>

                            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                                @if(!empty($search ?? request('search')))
                                    <a href="{{ route('job.list', ['search' => $search ?? request('search')]) }}" style="background: #2563EB; color: #FFFFFF; font-size: 13.5px; font-weight: 700; padding: 10px 22px; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                                        <i class="fa fa-briefcase"></i> {{__('Search for Jobs Matching')}} "{{ $search ?? request('search') }}"
                                    </a>
                                @endif
                                <a href="{{ route('job.seeker.list') }}" style="background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #475569; font-size: 13.5px; font-weight: 700; padding: 10px 20px; border-radius: 10px; text-decoration: none;">
                                    {{__('Reset All Filters')}}
                                </a>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </form>

    </div>
</div>

<style>
.candidate-item-card:hover {
    border-color: #BFDBFE !important;
    box-shadow: 0 6px 24px rgba(37,99,235,0.06) !important;
    transform: translateY(-1px);
}
</style>

@include('includes.footer')
@endsection