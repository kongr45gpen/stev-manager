class SubmittersController < ApplicationController
    def index
        @submitters = Submitter.all
    end
    
    def show
        @submitter = Submitter.find(params[:id])
    end
end
