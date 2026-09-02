<div class="modal-body">
    <div class="form-body">
        <div class="formrow mb-3" id="div_title">
            <label for="title">{{__('Designation / Job Title')}} <span class="text-danger">*</span></label>
            <input class="form-control" id="title" placeholder="{{__('e.g. Senior Software Engineer, Sales Executive')}}" name="title" type="text" value="{{(isset($profileExperience)? $profileExperience->title:'')}}">
            <span class="help-block title-error"></span>
        </div>

        <div class="formrow mb-3" id="div_company">
            <label for="company">{{__('Company Name')}} <span class="text-danger">*</span></label>
            <input class="form-control" id="company" placeholder="{{__('e.g. Google, Tata Consultancy Services')}}" name="company" type="text" value="{{(isset($profileExperience)? $profileExperience->company:'')}}">
            <span class="help-block company-error"></span>
        </div>

        <div class="row">
            <div class="col-md-4 col-12">
                <div class="formrow mb-3" id="div_country_id">
                    <label for="experience_country_id">{{__('Country')}} <span class="text-danger">*</span></label>
                    <?php
                    $country_id = (isset($profileExperience) ? $profileExperience->country_id : $siteSetting->default_country_id);
                    ?>
                    {!! Form::select('country_id', [''=>__('Select Country')]+$countries, $country_id, array('class'=>'form-control', 'id'=>'experience_country_id')) !!}
                    <span class="help-block country_id-error"></span>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="formrow mb-3" id="div_state_id">
                    <label for="experience_state_id">{{__('State')}}</label>
                    <span id="default_state_experience_dd">
                        {!! Form::select('state_id', [''=>__('Select State')], null, array('class'=>'form-control', 'id'=>'experience_state_id')) !!}
                    </span>
                    <span class="help-block state_id-error"></span>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="formrow mb-3" id="div_city_id">
                    <label for="city_id">{{__('City')}}</label>
                    <span id="default_city_experience_dd">
                        {!! Form::select('city_id', [''=>__('Select City')], null, array('class'=>'form-control', 'id'=>'city_id')) !!}
                    </span>
                    <span class="help-block city_id-error"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-12">
                <div class="formrow mb-3" id="div_date_start">
                    <label for="date_start">{{__('Start Date')}} <span class="text-danger">*</span></label>
                    <input class="form-control datepicker" autocomplete="off" id="date_start" placeholder="YYYY-MM-DD" name="date_start" type="text" value="{{(isset($profileExperience) && $profileExperience->date_start ? $profileExperience->date_start->format('Y-m-d'):'')}}">
                    <span class="help-block date_start-error"></span>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="formrow mb-3" id="div_date_end">
                    <label for="date_end">{{__('End Date')}}</label>
                    <input class="form-control datepicker" autocomplete="off" id="date_end" placeholder="YYYY-MM-DD" name="date_end" type="text" value="{{(isset($profileExperience) && $profileExperience->date_end ? $profileExperience->date_end->format('Y-m-d'):'')}}">
                    <span class="help-block date_end-error"></span>
                </div>
            </div>
        </div>

        <div class="formrow mb-3" id="div_is_currently_working">
            <label for="is_currently_working">{{__('Are you currently working here?')}}</label>
            <div class="d-flex align-items-center gap-3" style="display:flex; gap:16px; margin-top:4px;">
                <?php
                $val_1_checked = '';
                $val_2_checked = 'checked="checked"';

                if (isset($profileExperience) && $profileExperience->is_currently_working == 1) {
                    $val_1_checked = 'checked="checked"';
                    $val_2_checked = '';
                }
                ?>
                <label class="radio-inline" style="cursor:pointer; display:inline-flex; align-items:center; gap:6px; margin-right:15px;">
                    <input id="currently_working" name="is_currently_working" type="radio" value="1" {{$val_1_checked}}> {{__('Yes, Currently Working')}}
                </label>
                <label class="radio-inline" style="cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                    <input id="not_currently_working" name="is_currently_working" type="radio" value="0" {{$val_2_checked}}> {{__('No')}}
                </label>
            </div>
            <span class="help-block is_currently_working-error"></span>
        </div>

        <div class="formrow mb-2" id="div_description">
            <label for="description">{{__('Job Description / Responsibilities')}}</label>
            <textarea name="description" class="form-control" id="description" rows="3" placeholder="{{__('Briefly describe your key roles, daily duties and achievements...')}}">{{(isset($profileExperience)? $profileExperience->description:'')}}</textarea>
            <span class="help-block description-error"></span>
        </div>
    </div>
</div>