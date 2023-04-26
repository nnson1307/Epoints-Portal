<?php

namespace Modules\CustomerLead\Repositories\Pipeline;

interface PipelineRepoInterface
{
    /**
     * Danh sách pipeline
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Lay danh sach danh muc pipeline
     *
     * @return mixed
     */
    public function getListCategory();

    /**
     * Luu pipeline moi
     *
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * Cap nhat pipeline
     *
     * @param $data
     * @param $idPipeline
     * @return mixed
     */
    public function update($data);

    /**
     * Lay thong tin chi tiet pipeline
     *
     * @param $pipelineId
     * @return mixed
     */
    public function getDetail($pipelineId);

    /**
     * Lay danh sach hanh trinh theo pipeline id
     *
     * @param $pipelineId
     * @return mixed
     */
    public function getListJourney($pipelineId);

    /**
     * Xoa pipelinee
     *
     * @param $pipelineId
     * @return mixed
     */
    public function destroy($pipelineId);

    /**
     * Thiết lập pipeline mặc định
     *
     * @param $pipelineId
     * @return mixed
     */
    public function setDefaultPipeline($pipelineId, $pipelineCategoryCode);

    /**
     * Kiểm tra hành trình đã được sử dụng trong customer lead hay chưa
     *
     * @param $pipelineCode
     * @return mixed
     */
    public function checkJourneyBeUsed($pipelineCode);

    /**
     * lấy danh sách hành trình mặc định theo pipeline category code
     *
     * @param $pipelineCategoryCode
     * @return mixed
     */
    public function getListJourneyDefault($pipelineCategoryCode);

    /**
     * Lấy danh sách nhân viên
     *
     * @return mixed
     */
    public function getOptionStaff();
}